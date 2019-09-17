<?php

namespace App\Http\Controllers\Ghost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers;
use DB;
use Auth;

class KidsController extends Controller
{



    /**
     * Get all series and movies by age ratings (G)
     *
     * @return void
     */
    public function getAll()
    {
          // Get movie and series
        $getKidsMasQuery = DB::select('
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
                      "0" AS already_episode,
                      movies.m_cloud AS cloud
                      FROM movies
                      WHERE movies.m_age = "G" AND movies.show <> 0
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
                      WHEN u4.series_id IS NULL OR u4.show = 0 THEN false
                      ELSE true
                      END AS "already_episode",
                      series.t_cloud AS cloud
                      FROM series
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                      WHERE series.t_age = "G" 
                      GROUP BY series.t_id DESC
                      ) LIMIT 70');

        if (empty($getKidsMasQuery)) {
            $getKidsMasQuery = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'kids' => $getKidsMasQuery
                ]], 200);
    }
}
