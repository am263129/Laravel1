<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Series;
use App\Helpers;

class SeriesController extends Controller
{

    /**
     * This Constructer check if the user is make payment or not if not it will return 404
     *
     * SeriesController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Helpers::checkUserPayment(Auth::user());

            return $next($request);
        });
    }

    /**
     * Get all series
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSeries()
    {
        $seriesQuery = DB::select('
                      SELECT
                      series.t_id AS id,
                      series.t_name AS name, 
                      series.t_desc AS overview, 
                      series.t_backdrop AS backdrop, 
                      series.t_genre AS genre, 
                      series.t_year AS year, 
                      series.t_rate AS rate, 
                      series.t_poster AS poster, 
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
                      LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' . Auth::id() . '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' . Auth::id() . '"
                      LEFT JOIN likes AS u3  ON u3.series_id = series.t_id AND u3.uid = "' . Auth::id() . '"
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id AND u4.show = 1
                      WHERE series.t_age <> "G"
                      GROUP BY series.t_id
                      ORDER BY series.created_at DESC
                      LIMIT 100');

        // Check if there is no series
        if (empty($seriesQuery)) {
            $seriesQuery = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'series' => $seriesQuery
            ]]);
    }

    /**
     * Get series details
     *
     * @param [type] $id
     * @return void
     */
    public function getSeriesDetails($id)
    {
        //Check if moive already
        $check = Series::find($id);

        if (is_null($check)) {
            return response()->json(['status' => 404], 404);
        }

        $seriesQuery = DB::select('
                      SELECT DISTINCT
                      series.t_id AS id,
                      series.t_name AS name, 
                      series.t_desc AS overview, 
                      series.t_backdrop AS backdrop, 
                      series.t_genre AS genre, 
                      series.t_year AS year, 
                      series.t_rate AS rate, 
                      series.t_poster AS poster, 
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
                      LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' . Auth::id() . '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' . Auth::id() . '"
                      LEFT JOIN likes AS u3  ON u3.series_id = series.t_id AND u3.uid = "' . Auth::id() . '"
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                      WHERE series.t_id = "' . $id . '"
                      LIMIT 1');

        // Check if there is no series
        if (empty($seriesQuery)) {
            $seriesQuery = null;
        }


        // Get casts
        $getSeriesCast = DB::table('casts')
            ->select('casts.c_id AS id', 'casts.c_name AS name', 'casts.c_image AS image')
            ->join('casts_rules', 'casts_rules.casts_id', '=', 'casts.credit_id')
            ->where('casts_series', '=', $id)
            ->get();

        // Check if there is no cast
        if ($getSeriesCast->isEmpty()) {
            $getSeriesCast = null;
        }

        // Get casts
        $getAllSeason = DB::table('episodes')
            ->selectRaw('episodes.*, series.t_id')
            ->join('series', 'series.t_id', '=', 'episodes.series_id')
            ->where('series.t_id', $id)
            ->where('episodes.show', '<>', 0)
            ->orderByRaw('season_number, episode_number + 0 ASC')
            ->get();
        $season = [];

        // Check if only one
        if (count($getAllSeason) === 1) {

            $season[$getAllSeason[0]->season_number] = $getAllSeason;

        } else {

            // Check if there is no cast
            if ($getAllSeason->isEmpty()) {
                $season = null;
            } else {

                // Sort season and episode
                foreach ($getAllSeason as $key => $value) {
                    $season[$value->season_number][] = $getAllSeason[$key];
                }

            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'series' => $seriesQuery[0],
                'casts' => $getSeriesCast,
                'season' => $season
        ]]);
    }


    /**
     * Sort by trending and genre
     *
     * @return \Illuminate\Http\Response
     */

    public function sortSeries(Request $request)
    {
        $request->validate([
            'trending' => 'required|numeric|between:1,5',
            'genre' => 'required|string|max:15'
        ]);

        if ($request->input('genre') === 'all') {
            $request->genre = "";
        }

        if ($request->input('trending') === 1) {
            $trending = 't_id';
        } elseif ($request->input('trending') === 2) {
            $trending = 't_year';
        } elseif ($request->input('trending') === 3) {
            $trending = 't_rate';
        } elseif ($request->input('trending') === 4) {
            $trending = 'likes.series_id';
        }

        $seriesQuery = DB::select('
                            SELECT DISTINCT 
                            series.t_id AS id,
                            series.t_name AS name, 
                            series.t_desc AS overview, 
                            series.t_backdrop AS backdrop, 
                            series.t_genre AS genre, 
                            series.t_year AS year, 
                            series.t_rate AS rate, 
                            series.t_poster AS poster, 
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
                            LEFT JOIN likes ON likes.series_id = series.t_id
                            LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' . Auth::id() . '"
                            LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' . Auth::id() . '"
                            LEFT JOIN likes AS u3  ON u3.series_id = series.t_id AND u3.uid = "' . Auth::id() . '"
                            LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id AND u4.show = 1
                            WHERE series.t_genre LIKE "' . $request->genre . '%" AND series.t_age <> "G"
                            GROUP BY series.t_id
                            ORDER BY ' . $trending . ' DESC
                            LIMIT 100');

        // Check if there is no series
        if (empty($seriesQuery)) {
            $seriesQuery = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'series' => $seriesQuery
            ]]);
    }
}
