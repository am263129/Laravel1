<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers;
use DB;
use Auth;

class KidsController extends Controller
{

    /**
     * This Constructer check if the user is make payment or not if not it will return 404
     *
     * KidsController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Helpers::checkUserPayment(Auth::user());
            return $next($request);
        });

    }

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
                      movies.m_cloud AS cloud,
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
                      "0" AS already_episode
                      FROM movies
                      LEFT JOIN collection_lists AS u1  ON u1.movie_id = movies.m_id AND u1.uid = "' .Auth::id(). '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.movie_id = movies.m_id AND u2.uid = "' .Auth::id(). '"
                      LEFT JOIN likes AS u3  ON u3.movie_id = movies.m_id AND u3.uid = "' .Auth::id(). '"
                      WHERE movies.m_age = "G" AND movies.show <> 0
                      GROUP BY movies.m_id DESC
                      ORDER BY movies.created_at DESC)
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
                      series.t_cloud AS cloud,
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
                      END AS "already_episode"
                      FROM series
                      LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' .Auth::id(). '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' .Auth::id(). '"
                      LEFT JOIN likes AS u3  ON u3.series_id = series.t_id AND u3.uid = "' .Auth::id(). '"
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                      WHERE series.t_age = "G" 
                      GROUP BY series.t_id DESC
                      ORDER BY series.created_at DESC
                      ) LIMIT 100');

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
