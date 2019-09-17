<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Movie;
use App\Series;
use App\Like;
use App\Helpers;
class LikesController extends Controller
{

    /**
     * This Constructer check if the user is make payment or not if not it will return 404
     *
     * LikesController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Helpers::checkUserPayment(Auth::user());

            return $next($request);
        });

    }

    /**
     *  Add like to movie and series also remove if is already
     *
     * @param Request $request
     * @return void
     */
    public function addLike(Request $request)
    {
        $request->validate([
            'id'   => 'required|uuid',
            'type' => 'required|string|max:6',
        ]);

        if ($request->input('type') === 'movie') {

            // Check if movie is exist in database

            $checkMovieAlready = Movie::where('m_id', $request->input('id'))->first();

            if (! is_null($checkMovieAlready)) {
                $checkMovieLikeAlready = Like::where('movie_id', $request->input('id'))->where('uid', Auth::id())->first();
        
                if (! is_null($checkMovieLikeAlready)) {
                    // Delete like if is already

                    $checkMovieLikeAlready->delete();
                    return response()->json(['status' => 'success', 'message' => 'Success delete like']);
                } else {
                    // Add like to movie

                    $addNewMovieLike = new Like();
                    $addNewMovieLike->movie_id = $request->input('id');
                    $addNewMovieLike->uid = Auth::id();
                    $addNewMovieLike->save();
                    return response()->json(['status' => 'success', 'message' => 'Success add like'], 200);
                }
            } else {
                return response()->json(['status' => 404], 404);
            }
        } elseif ($request->input('type') === 'series') {


            // Check if series is exist in database

            $checkSeriesAlready = Series::where('t_id', $request->input('id'))->first();

            if (! is_null($checkSeriesAlready)) {
                $checkSeriesLikeAlready = Like::where('series_id', $request->input('id'))->where('uid', Auth::id())->first();
        
                if (! is_null($checkSeriesLikeAlready)) {
                    // Delete like if is already

                    $checkSeriesLikeAlready->delete();
                    return response()->json(['status' => 'success', 'message' => 'Success delete like']);
                } else {
                    // Add like to movie

                    $addNewSeriesLike = new Like();
                    $addNewSeriesLike->series_id = $request->input('id');
                    $addNewSeriesLike->uid = Auth::id();
                    $addNewSeriesLike->save();

                    return response()->json(['status' => 'success', 'message' => 'Success add like']);
                }
            } else {
                return response()->json(['status' => 404], 404);
            }
        }
    }
}
