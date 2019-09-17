<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers;
use Auth;

class SearchController extends Controller
{

    /**
     * This Constructer check if the user is make payment or not if not it will return 404
     *
     * SearchController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Helpers::checkUserPayment(Auth::user());

            return $next($request);
        });

    }


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
                      u2.current_time,
                      u2.duration_time,
                      CASE
                      WHEN u1.id IS NULL THEN false
                      ELSE true
                      END AS "is_favorite",
                      CASE
                      WHEN u3.id IS NULL THEN false
                      ELSE true
                      END AS "is_like",
                      0 AS already_episode,
                      movies.m_cloud AS cloud
                      FROM movies
                      LEFT JOIN collection_lists AS u1  ON u1.movie_id = movies.m_id AND u1.uid = "' .Auth::id(). '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.movie_id = movies.m_id AND u2.uid = "' .Auth::id(). '"
                      LEFT JOIN likes AS u3  ON u3.movie_id = movies.m_id AND u3.uid = "' .Auth::id(). '"
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
                      u2.current_time,
                      u2.duration_time,
                      CASE
                      WHEN u1.id IS NULL THEN false
                      ELSE true
                      END AS "is_favorite",
                      CASE
                      WHEN u3.id IS NULL THEN false
                      ELSE true
                      END AS "is_like",
                      CASE
                      WHEN u4.series_id IS NULL OR u4.show = 0 THEN false
                      ELSE true
                      END AS "already_episode",
                      series.t_cloud AS cloud
                      FROM series
                      LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' .Auth::id(). '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' .Auth::id(). '"
                      LEFT JOIN likes AS u3  ON u3.series_id = series.t_id AND u3.uid = "' .Auth::id(). '"
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id AND u4.show = 1
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
