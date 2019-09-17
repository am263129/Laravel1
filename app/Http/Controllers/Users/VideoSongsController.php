<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Watchlater;
use App\VideoSong;
use App\Helpers;

class VideoSongsController extends Controller
{

    /**
     * This Constructer check if the user is make payment or not if not it will return 404
     *
     * MoviesController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Helpers::checkUserPayment(Auth::user());
            return $next($request);
        });

    }

    /**
     * Get all movies
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllVideoSongs()
    {
        $videoSongQuery = DB::select('
                      SELECT         
                      video_songs.v_id AS id, 
                      video_songs.v_name AS name,
                      video_songs.v_poster AS poster, 
                      video_songs.v_desc AS overview, 
                      video_songs.v_runtime AS runtime, 
                      video_songs.v_year AS year,
                      video_songs.v_genre AS genre, 
                      video_songs.v_rate AS rate, 
                      video_songs.v_backdrop AS backdrop,
                      video_songs.v_age AS age,                       
                      video_songs.v_cloud AS cloud
                      FROM video_songs                      
                      WHERE video_songs.show <> 0
                      GROUP BY video_songs.created_at DESC
                      LIMIT 100');

        // Check if there is no movies
        if (empty($videoSongQuery)) {
            $videoSongQuery = null;
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'videosongs' => $videoSongQuery
            ]]);
    }


    /**
     * Get movie details
     *
     * @param uuid $id
     * @return void
     */
    public function getMovieDetails($id)
    {

        //Check if moive already
        $check = Movie::find($id);

        if (is_null($check)) {
            return response()->json(['status' => 404], 404);
        }

        $movieQuery = DB::select('
                      SELECT DISTINCT        
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
                      movies.m_youtube AS trailer,
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
                      COUNT(u3.movie_id) AS likes_number,
                      movies.m_cloud AS cloud
                      FROM movies
                      LEFT JOIN collection_lists AS u1  ON u1.movie_id = movies.m_id AND u1.uid = "' .Auth::id(). '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.movie_id = movies.m_id AND u2.uid = "' .Auth::id(). '"
                      LEFT JOIN likes AS u3  ON u3.movie_id = movies.m_id AND u3.uid = "' .Auth::id(). '"
                      WHERE movies.m_id = "'. $id .'" AND movies.show <> 0 
                      LIMIT 1');

        // Check if there is no movies
        if (empty($movieQuery)) {
            $movieQuery = null;
        }


        // Get casts
        $getMovieCast = DB::table('casts')
                    ->select('casts.c_id AS id', 'casts.c_name AS name', 'casts.c_image AS image')
                    ->join('casts_rules', 'casts_rules.casts_id', '=', 'casts.credit_id')
                    ->where('casts_movies', '=', $id)
                    ->get();

        // Check if there is no cast
        if ($getMovieCast->isEmpty()) {
            $getMovieCast = null;
        }
        // Get casts
        $getSimilarMovies = DB::table('movies')
                    ->selectRaw('m_id AS id, m_name AS name,m_poster AS poster, m_cloud AS cloud')
                    ->whereRaw('m_genre LIKE "'.strtok($movieQuery[0]->genre, "-").'%"')
                    ->whereRaw('m_id <> "'. $movieQuery[0]->id .'"')
                    ->limit(4)
                    ->get();

        // Check if there is no cast
        if ($getSimilarMovies->isEmpty()) {
            $getSimilarMovies = null;
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'movie' => $movieQuery[0],
                'casts' => $getMovieCast,
                'similar' => $getSimilarMovies
            ]]);
    }



    /**
     * Sort by trending and genre
     *
     * @return \Illuminate\Http\Response
     */
    public function sortMovies(Request $request)
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
            $trending = 'movies.updated_at';
        } elseif ($request->input('trending') === 2) {
            $trending = 'm_year';
        } elseif ($request->input('trending') === 3) {
            $trending = 'm_rate';
        } elseif ($request->input('trending') === 4) {
            $trending = 'likes.movie_id';
        }
        $movieQuery = DB::select('
                      SELECT DISTINCT        
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
                      movies.m_cloud AS cloud
                      FROM movies
                      LEFT JOIN likes ON likes.movie_id = movies.m_id
                      LEFT JOIN collection_lists AS u1  ON u1.movie_id = movies.m_id AND u1.uid = "' .Auth::id(). '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.movie_id = movies.m_id AND u2.uid = "' .Auth::id(). '"
                      LEFT JOIN likes AS u3  ON u3.movie_id = movies.m_id AND u3.uid = "' .Auth::id(). '"
                      WHERE movies.m_genre LIKE "'. $request->genre .'%" AND movies.show <> 0 AND movies.m_age <> "G"
                      ORDER BY ' .$trending. ' DESC 
                      LIMIT 100');

        // Check if there is no movies
        if (empty($movieQuery)) {
            $movieQuery = null;
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'movies' => $movieQuery
            ]]);
    }
}
