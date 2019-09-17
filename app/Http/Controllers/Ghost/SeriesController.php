<?php

namespace App\Http\Controllers\Ghost;

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
                      series.t_cloud AS cloud,
                      CASE
                      WHEN u4.series_id IS NULL OR u4.show = 0 THEN false
                      ELSE true
                      END AS "already_episode"
                      FROM series
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                      WHERE series.t_age <> "G"
                      GROUP BY series.updated_at DESC
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
                      series.t_cloud AS cloud,
                      CASE
                      WHEN u4.series_id IS NULL OR u4.show = 0 THEN false
                      ELSE true
                      END AS "already_episode"
                      FROM series
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

        // Check if genre request equal the array genre
        $genreArray = ['all', 'action', 'action', 'adventure', 'animation', 'biography', 'comedy', 'crime', 'documentary', 'drama', 'family', 'fantasy', 'history', 'horror', 'music', 'mystery', 'romance', 'sci-fi', 'sport', 'thriller', 'war'];
        if (!in_array($request->input('genre'), $genreArray)) {
            return response()->json(['status' => 'error', 'message' => 'Genre not found'], 400);
        }

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
                            series.t_cloud AS cloud,
                            CASE
                            WHEN u4.series_id IS NULL OR u4.show = 0 THEN false
                            ELSE true
                            END AS "already_episode"
                            FROM series
                            LEFT JOIN likes ON likes.series_id = series.t_id
                            LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
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
