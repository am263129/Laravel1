<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers;
use App\Subtitle;
use \Done\Subtitles\Subtitles;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class SubtitlesController extends Controller
{

        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin','manager']);
            return $next($request);
        });
    }


    /**
     * Get subtitle of movie
     *
     * @param [type] $id
     * @return void
     */
    public function getMovieSubtitle($id)
    {
        $getSubtitles = DB::table('subtitles')
        ->selectRaw('id,name,language,subtitles.created_at')
        ->join('movies', 'movies.m_id', '=', 'subtitles.movie_id')
        ->where('movies.m_id', $id)
        ->get();

        if ($getSubtitles->isEmpty()) {
            $getSubtitles = null;
        }


        return response()->json([
            'status' => 'success',
            'data' => [
                'subtitles' => $getSubtitles,
            ]
        ]);
    }

    /**
     *  Delete some subtitle of movie
     *
     * @param [type] $id
     * @return void
     */
    public function deleteSubtitle($id)
    {
        $subtitle = Subtitle::where('id', $id)->first();
        if (is_null($subtitle)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no subtitle'], 422);
        }
        $subtitle->delete();
        return response()->json(['status' => 'success']);
    }


    /**
     * Get episode subtitle
     *
     * @param [type] $id
     * @return void
     */
    public function getEpisodeSubtitle($id)
    {
        $getSubtitles = DB::table('subtitles')
        ->selectRaw('subtitles.id,subtitles.name,subtitles.language,subtitles.created_at')
        ->join('episodes', 'episodes.id', '=', 'subtitles.episode_id')
        ->where('episodes.id', $id)
        ->get();

        if ($getSubtitles->isEmpty()) {
            $getSubtitles = null;
        }


        return response()->json([
            'status' => 'success',
            'data' => [
                'subtitles' => $getSubtitles,
            ]
        ]);
    }
}
