<?php

namespace App\Http\Controllers\Ghost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers;
use Auth;

class SearchController extends Controller
{


    /*  Get search with movies, serials and acting staff
     *  @param  Request  $request
     *  @return Response array - movies, serials and acting staff
     */

    public function getSearch(Request $request)
    {

        // Validate query
        // Regex accept letters-number-spaces

        $request->validate([
            'query' => 'nullable|max:30|regex:/^[\w\s.,-]*$/',
         ]);

        // Get movie and series
        $getMasQuery = DB::select('
                      (SELECT         
                      "movie" AS type, 
                      movies.m_id AS id, 
                      movies.m_name AS name,
                      movies.m_poster AS poster, 
                      movies.m_desc AS overview, 
                      movies.m_runtime AS runtime, 
                      movies.m_year AS year,
                      movies.m_genre AS genre, 
                      movies.m_rate AS rate, 
                      movies.m_backdrop AS backdrop,
                      movies.m_age AS age, 
                      0 AS already_episode,
                      movies.m_cloud AS cloud
                      FROM movies
                      WHERE movies.m_name LIKE "'. $request->input('query') .'%"
                      GROUP BY movies.m_id DESC)
                      UNION
                      (SELECT
                      "series" AS type,
                      series.t_id AS id,
                      series.t_name AS name, 
                      series.t_poster AS poster, 
                      series.t_desc AS overview, 
                      "null" AS runtime,                     
                      series.t_year AS year, 
                      series.t_genre AS genre, 
                      series.t_rate AS rate,                   
                      series.t_backdrop AS backdrop, 
                      series.t_age AS age,
                      CASE
                      WHEN u4.series_id IS NULL THEN false
                      ELSE true
                      END AS "already_episode",
                      series.t_cloud AS cloud
                      FROM series
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                      WHERE series.t_name LIKE "'. $request->input('query') .'%"
                      GROUP BY series.t_id DESC
                      ) LIMIT 8');

        $getCast =  DB::table('casts')
            ->selectRaw('"casts" AS type, casts.c_id AS id,casts.c_name AS name,casts.c_image AS image, casts.c_cloud AS cloud')
        ->where('casts.c_name', 'like', $request->input('query').'%')
        ->limit(4)
        ->get();

        // Check if series and movies array is empty
        if (empty($getMasQuery)) {
            $getMasQuery = null;
        }

        // Check if cast array is empty

        if ($getCast->isEmpty()) {
            $getCast = null;
        }

        return response()->json(
            [ 'status' => 'success',
              'data'   => [
                  'data' => $getMasQuery,
                  'cast' => $getCast
              ]],

            200

        );
    }
}
