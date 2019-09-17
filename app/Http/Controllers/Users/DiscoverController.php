<?php

namespace App\Http\Controllers\Users;

use App\Appearance;
use App\Helpers;
use App\Movie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DiscoverController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {            
            Helpers::checkUserPayment(Auth::user());            
            return $next($request);
        });

    }


    /**
     * This mix of series and movies
     *
     * @return \Illuminate\Http\Response
     */
    public function getHomeResult()
    {


        // Execute query and push it in array
        $getMasByGenre = [];

        $movieQuery = DB::select('
                      SELECT
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
                      categories.name AS category_name,
                      categories.kind AS category_type,
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
                      movies.m_cloud AS cloud
                      FROM movies
                      JOIN categories ON categories.id = movies.m_category
                      LEFT JOIN collection_lists AS u1  ON u1.movie_id = movies.m_id AND u1.uid = "' . Auth::id() . '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.movie_id = movies.m_id AND u2.uid = "' . Auth::id() . '"
                      LEFT JOIN likes AS u3  ON u3.movie_id = movies.m_id AND u3.uid = "' . Auth::id() . '"
                      WHERE movies.m_age <> "G" AND movies.show <> 0
                      GROUP BY movies.m_id DESC 
                      ORDER BY movies.created_at DESC
                      LIMIT 50');


        if (empty($movieQuery)) {
            $movieQuery = null;
        } else {

            // Sort Category
            $mtArray = [];
            foreach ($movieQuery as $key => $value) {
                if (!in_array($value->category_name, $mtArray)) {
                    $mtArray[] = $value->category_name;
                    array_push($getMasByGenre, [
                        'list' => [],
                        'genre' => $value->category_name,
                        'type' => 'Movies'
                    ]);
                }
            }

            for ($key = 0; $key < count($movieQuery); $key++) {
                for ($key1 = 0; $key1 < count($getMasByGenre); $key1++) {
                    if ($getMasByGenre[$key1]['genre'] == $movieQuery[$key]->category_name) {
                        $arr = array();
                        $arr['id'] = $movieQuery[$key]->id;
                        $arr['name'] = $movieQuery[$key]->name;
                        $arr['poster'] = $movieQuery[$key]->poster;
                        $arr['overview'] = $movieQuery[$key]->overview;
                        $arr['runtime'] = $movieQuery[$key]->runtime;
                        $arr['year'] = $movieQuery[$key]->year;
                        $arr['genre'] = $movieQuery[$key]->genre;
                        $arr['rate'] = $movieQuery[$key]->rate;
                        $arr['backdrop'] = $movieQuery[$key]->backdrop;
                        $arr['age'] = $movieQuery[$key]->age;
                        $arr['cloud'] = $movieQuery[$key]->cloud;
                        $arr['category_name'] = $movieQuery[$key]->category_name;
                        $arr['category_type'] = $movieQuery[$key]->category_type;
                        $arr['current_time'] = $movieQuery[$key]->current_time;
                        $arr['duration_time'] = $movieQuery[$key]->duration_time;
                        $arr['is_like'] = $movieQuery[$key]->is_like;
                        $arr['is_favorite'] = $movieQuery[$key]->is_favorite;
                        array_push($getMasByGenre[$key1]['list'], $arr);
                    }
                }
            }
        }



        // if type eqaule series get the series by genre
        $seriesQuery = DB::select('
                                SELECT
                                "series" AS type,
                                series.t_id AS id,
                                series.t_name AS name,
                                series.t_desc AS overview,
                                series.t_backdrop AS backdrop,
                                series.t_genre AS genre,
                                series.t_year AS year,
                                series.t_rate AS rate,
                                series.t_poster AS poster,
                                series.t_age AS age,
                                categories.name AS category_name,
                                categories.kind AS category_type,
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
                                JOIN categories ON categories.id = series.t_category
                                LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' . Auth::id() . '"
                                LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' . Auth::id() . '"
                                LEFT JOIN likes    AS u3  ON u3.series_id = series.t_id AND u3.uid = "' . Auth::id() . '"
                                LEFT JOIN episodes AS u4  ON u4.series_id = series.t_id
                                WHERE series.t_age <> "G" 
                                GROUP BY series.t_id DESC
                                ORDER BY series.created_at DESC
                                LIMIT 50');


        // Check if there is no series
        if (empty($seriesQuery)) {
            $seriesQuery = null;
        } else {
            // Sort Category
            $mtArray = [];
            foreach ($seriesQuery as $key => $value) {
                if (!in_array($value->category_name, $mtArray)) {
                    $mtArray[] = $value->category_name;
                    array_push($getMasByGenre, [
                        'list' => [],
                        'genre' => $value->category_name,
                        'type' => 'Series'
                    ]);
                }
            }

            for ($key = 0; $key < count($seriesQuery); $key++) {
                for ($key1 = 0; $key1 < count($getMasByGenre); $key1++) {
                    if ($getMasByGenre[$key1]['genre'] == $seriesQuery[$key]->category_name && $getMasByGenre[$key1]['type']  == "Series") {
                        $arr = array();
                        $arr['id'] = $seriesQuery[$key]->id;
                        $arr['name'] = $seriesQuery[$key]->name;
                        $arr['poster'] = $seriesQuery[$key]->poster;
                        $arr['overview'] = $seriesQuery[$key]->overview;
                        $arr['year'] = $seriesQuery[$key]->year;
                        $arr['genre'] = $seriesQuery[$key]->genre;
                        $arr['rate'] = $seriesQuery[$key]->rate;
                        $arr['backdrop'] = $seriesQuery[$key]->backdrop;
                        $arr['age'] = $seriesQuery[$key]->age;
                        $arr['cloud'] = $seriesQuery[$key]->cloud;
                        $arr['category_name'] = $seriesQuery[$key]->category_name;
                        $arr['category_type'] = $seriesQuery[$key]->category_type;
                        $arr['current_time'] = $seriesQuery[$key]->current_time;
                        $arr['duration_time'] = $seriesQuery[$key]->duration_time;
                        $arr['is_like'] = $seriesQuery[$key]->is_like;
                        $arr['is_favorite'] = $seriesQuery[$key]->is_favorite;
                        array_push($getMasByGenre[$key1]['list'], $arr);
                    }
                }
            }
        }


        // Get top movies and series
        $getTopMas = DB::select('(SELECT
                                "movie" AS type,
                                movies.m_id AS id,
                                movies.m_name AS name,
                                movies.m_poster AS poster,
                                movies.m_desc AS overview,
                                movies.m_year AS year,
                                movies.m_genre AS genre,
                                movies.m_rate AS rate,
                                movies.m_backdrop AS backdrop,
                                movies.m_poster AS poster,
                                movies.m_age AS age,
                                u2.current_time,
                                u2.duration_time,
                                CASE
                                WHEN u1.id IS NULL THEN false
                                ELSE true
                                END AS "is_favorite",
                                movies.m_cloud AS cloud
                                FROM tops
                                INNER JOIN movies  ON movies.m_id = tops.movie_id
                                LEFT JOIN collection_lists AS u1  ON u1.movie_id = movies.m_id AND u1.uid = "' . Auth::id() . '"
                                LEFT JOIN recently_watcheds AS u2 ON u2.movie_id = movies.m_id AND u2.uid = "' . Auth::id() . '"
                                GROUP BY movies.m_id DESC)
                                UNION
                                (SELECT
                                "series" AS type,
                                series.t_id AS id,
                                series.t_name AS name,
                                series.t_poster AS poster,
                                series.t_desc AS overview,
                                series.t_year AS year,
                                series.t_genre AS genre,
                                series.t_rate AS rate,
                                series.t_backdrop AS backdrop,
                                series.t_poster AS poster,
                                series.t_age AS age,
                                u2.current_time,
                                u2.duration_time,
                                CASE
                                WHEN u1.id IS NULL THEN false
                                ELSE true
                                END AS "is_favorite",
                                series.t_cloud AS cloud
                                FROM tops
                        	    INNER JOIN series  ON series.t_id = tops.series_id
                                LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' . Auth::id() . '"
                                LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' . Auth::id() . '"
                                LEFT JOIN episodes AS u4  ON u4.series_id = series.t_id
                                GROUP BY series.t_id DESC)');

        if (empty($getTopMas)) {
            $getTopMas = null;
        }

        // Last 3 Recently Watched
        $getRecentlyQuery = DB::select('
                      (SELECT
                      "movie" AS type,
                      movies.m_id AS id,
                      "null" as episode_id,
                      movies.m_name AS name,
                      movies.m_backdrop AS backdrop,
                      movies.m_cloud AS cloud,
                      recently_watcheds.current_time,
                      recently_watcheds.duration_time
                      FROM recently_watcheds
                      JOIN movies ON movies.m_id = recently_watcheds.movie_id
                      WHERE recently_watcheds.uid = "' . Auth::id() . '"
                      )
                      UNION
                      (SELECT
                      "series" AS type,
                      episodes.series_id,
                      episodes.id as episode_id,
                      episodes.name,
                      episodes.backdrop,
                      episodes.cloud,
                      recently_watcheds.current_time,
                      recently_watcheds.duration_time
                      FROM recently_watcheds
                      JOIN episodes ON episodes.id = recently_watcheds.episode_id
                      WHERE recently_watcheds.uid = "' . Auth::id() . '"
                      ) LIMIT 3');

        if (empty($getRecentlyQuery)) {
            $getRecentlyQuery = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'data' => $getMasByGenre,
                'top' => $getTopMas,
                'recenlty' => $getRecentlyQuery
            ]], 200);
    }

    /**
     * Get Notifcation
     *
     * @return void
     */
    public function getNotifaction()
    {

        // Get support

        $getSupport = DB::table('supports')
            ->selectRaw('supports.*, support_responses.readit, support_responses.reply')
            ->leftJoin('support_responses', function ($join) {
                $join->on('support_responses.request_id', '=', 'supports.request_id')
                    ->where('support_responses.from', '=', 'support');
            })
            ->where('supports.uid', Auth::id())
            ->where('support_responses.readit', 1)
            ->groupBy('supports.id')
            ->get();

        if ($getSupport->isEmpty()) {
            $getSupport = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'support' => $getSupport
            ]], 200);
    }


}
