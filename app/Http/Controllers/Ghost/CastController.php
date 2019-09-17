<?php

namespace App\Http\Controllers\Ghost;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers;
use Auth;

class CastController extends Controller
{

    /**
     *  Get cast details the name & image also the filmgraphy
     *  @param $id the id of cast
     *  @return array - cast details and filmgraphy
     */

    public function getCastDetails($id)
    {
        // Get cast details
        $getCastDetails = DB::table('casts')
            ->selectRaw('c_id AS id, c_name AS name, c_image AS image,credit_id')
            ->where('c_id', $id)
            ->first();


        // Check if actor object is empty
        if (is_null($getCastDetails)) {
            return response()->json(['status' => 404], 404);
        }

        // Get filmography of actor
        $getCastFilmography = DB::select('
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
                      movies.m_type AS player
                      FROM casts_rules
                      WHERE casts_rules.casts_id = "'. $getCastDetails->credit_id .'" AND movies.show <> 0
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
                      FROM casts_rules
                      JOIN series ON series.t_id = casts_rules.casts_series
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                      WHERE casts_rules.casts_id = "'. $getCastDetails->credit_id .'"
                      GROUP BY series.t_id DESC
                      ) LIMIT 8');



        // Check if filmography array is empty
        if (empty($getCastFilmography)) {
            $getCastFilmography = null;
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                 'cast'        => $getCastDetails,
                 'filmography' => $getCastFilmography
            ]], 200);
    }
}
