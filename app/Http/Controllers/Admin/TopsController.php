<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Top;
use App\Movie;
use App\Series;

use Auth;

class TopsController extends Controller
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
     * Add series to top
     *
     * @param Request $request
     * @return void
     */
    public function addSeriesToTop(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        
        // First check if series is already in database
        $checkSeries = Series::where('t_id', $request->id)->first();
        
        if (! is_null($checkSeries)) {
        
        // Second check if movie is already in top or not
            $checkTop = Top::where('series_id', $request->id)->first();
        
            if (is_null($checkTop)) {
                $add = new Top;
                $add->series_id = $request->id;
                $add->save();
                return response()->json(['status' => 'success', 'message' => 'Successful add '. $checkSeries->t_name. ' to top']);
            }
        }
        // if is ready in the top
        return response()->json(['status' => 'failed']);
    }


    /**
     * Add movie to top
     *
     * @param Request $request
     * @return void
     */
    public function addMovieToTop(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        
        // First check if movie is already in database
        $checkMovie = Movie::where('m_id', $request->id)->first();
        
        if (! is_null($checkMovie)) {
        
        // Second check if movie is already in top or not
            $checkTop = Top::where('movie_id', $request->id)->first();
        
            if (is_null($checkTop)) {
                $add = new Top;
                $add->movie_id = $request->id;
                $add->save();
                return response()->json(['status' => 'success', 'message' => 'Successful add '. $checkMovie->m_name. ' to top']);
            }
        }
        // if is ready in the top
        return response()->json(['status' => 'failed']);
    }

    /**
     * Delete movie and series from top
     *
     * @param [uuid] $id
     * @return void
     */
    public function deleteTop($id)
    {
        $series = Top::where('series_id', $id)->first();
        $movie = Top::where('movie_id', $id)->first();

        if (! is_null($series)) {
            Top::where('series_id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Success delete from top']);
        } elseif (! is_null($movie)) {
            Top::where('movie_id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Success delete from top']);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No id found'], 422);
        }
    }
    
    /**
     * Get top movie and series
     *
     * @return void
     */
    public function getTopMas()
    {
        $get1 = DB::table('tops')
            ->selectRaw('t_name AS name, t_poster AS poster, tops.created_at,tops.series_id AS id, series.t_cloud AS cloud')
            ->join('series', 'series.t_id', '=', 'tops.series_id');
        $getTops = DB::table('tops')
            ->selectRaw('m_name AS name, m_poster AS poster, tops.created_at,tops.movie_id AS id, movies.m_cloud AS cloud')
            ->join('movies', 'movies.m_id', '=', 'tops.movie_id')
            ->union($get1)
            ->get();

        if ($getTops->isEmpty()) {
            $getTops = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'top' => $getTops
            ]
        ]);
    }
}
