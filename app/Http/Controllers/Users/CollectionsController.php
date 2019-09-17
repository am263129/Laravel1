<?php

namespace App\Http\Controllers\Users;

use App\Collection;
use App\Collection_list;
use App\Helpers;
use App\Http\Controllers\Controller;
use App\Movie;
use App\Series;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionsController extends Controller
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


    /* Get collection movie and series
     * @return json response
     *
     *************************************/
    public function getCollectionList($id)
    {
        // Get collection detials
        $getCollectionDetails = DB::table('collections')
            ->select('id', 'name')
            ->where('id', $id)
            ->where('uid', Auth::id())
            ->first();

        if (is_null($getCollectionDetails)) {
            return response()->json(['status' => 'Not found'], 400);
        }

        // Get movie and series in collection
        $getMasCollection = DB::select('
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
                      u2.current_time,
                      u2.duration_time,
                      true AS "is_favorite",
                      CASE
                      WHEN u3.id IS NULL THEN false
                      ELSE true
                      END AS "is_like",
                      0 AS already_episode,
                      movies.m_cloud AS cloud
                      FROM collection_lists
                      JOIN movies ON movies.m_id = collection_lists.movie_id
                      LEFT JOIN recently_watcheds AS u2 ON u2.movie_id = collection_lists.movie_id
                      LEFT JOIN likes AS u3 ON u3.movie_id = collection_lists.movie_id
                      WHERE collection_lists.collection_id = "' . $id . '" AND collection_lists.uid = "' . Auth::id() . '" AND movies.show <> 0
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
                      u2.current_time,
                      u2.duration_time,
                      true AS "is_favorite",
                      CASE
                      WHEN u3.id IS NULL THEN false
                      ELSE true
                      END AS "is_like",
                      CASE
                      WHEN u4.series_id IS NULL OR u4.show = 0 THEN false
                      ELSE true
                      END AS "already_episode",
                      series.t_cloud AS cloud
                      FROM collection_lists
                      JOIN series ON series.t_id = collection_lists.series_id
                      LEFT JOIN recently_watcheds AS u2 ON u2.series_id = collection_lists.series_id
                      LEFT JOIN likes AS u3 ON u3.series_id = collection_lists.series_id
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                      WHERE collection_lists.collection_id = "' . $id . '" AND collection_lists.uid = "' . Auth::id() . '" 
                      GROUP BY series.t_id DESC)');

        if (empty($getMasCollection)) {
            $getMasCollection = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'data' => $getMasCollection,
                'collection' => $getCollectionDetails
            ]], 200);
    }

    /**
     * Get all colection name
     *
     * @return \Illuminate\Http\Response
     */
    public function getCollection()
    {
        // Get collection name
        $collection = DB::table('collections')
            ->select('id', 'name')
            ->where('uid', '=', Auth::id())
            ->get();

        return response()->json(['status' => 'success', 'data' => $collection], 200);
    }

    /**
     * This function to add new movie or series to collection,
     * Also to create new collection of user set the item in new name collection
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function addNewToCollection(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
            'new_collection' => 'string|nullable|max:20',
            'already_collection' => 'numeric|nullable',
            'type' => 'required|string|max:6',
        ]);

        // Check if movie and series id is exist
        $checkAlreadyMovie = Movie::where('m_id', $request->input('id'))->first();
        $checkAlreadySeries = Series::where('t_id', $request->input('id'))->first();

        if (is_null($checkAlreadyMovie)) {
            if (is_null($checkAlreadySeries)) {
                return response()->json(['status' => 'error', 'message' => 'There is no id'], 404);
            }
        }

        if ($request->input('new_collection') !== null || !empty($request->input('new_collection'))) {

            // Check if the new collection name is already or not
            $checkCollection = Collection::where('name', $request->input('new_collection'))->where('uid', Auth::id())->first();

            if (!is_null($checkCollection)) {
                return response()->json(['status' => 'error', 'message' => 'The collection is already'], 422);
            }

            if ($request->input('type') === 'movie') {

                // Create new collection
                $createNewCollection = new Collection();
                $createNewCollection->name = $request->input('new_collection');
                $createNewCollection->uid = Auth::id();
                $createNewCollection->save();

                // Add to movie to collection
                $addMovieToCollection = new Collection_list();
                $addMovieToCollection->movie_id = $request->input('id');
                $addMovieToCollection->collection_id = $createNewCollection->id;
                $addMovieToCollection->uid = Auth::id();
                $addMovieToCollection->save();

                return response()->json(['status' => 'success', 'message' => 'Successful added ' . $checkAlreadyMovie->m_name . ' to ' . $createNewCollection->name . ' collection', 'data' => $createNewCollection]);
            } elseif ($request->input('type') === 'series') {

                // Create new collection
                $createNewCollection = new Collection();
                $createNewCollection->name = $request->input('new_collection');
                $createNewCollection->uid = Auth::id();
                $createNewCollection->save();

                // Add to series to collection
                $addSeriesToCollection = new Collection_list();
                $addSeriesToCollection->series_id = $request->input('id');
                $addSeriesToCollection->collection_id = $createNewCollection->id;
                $addSeriesToCollection->uid = Auth::id();
                $addSeriesToCollection->save();

                return response()->json(['status' => 'success', 'message' => 'Successful added ' . $checkAlreadySeries->t_name . ' to ' . $createNewCollection->name . ' collection', 'data' => $createNewCollection]);
            } else {
                // Error if there is wrong in type
                return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
            }
        } elseif ($request->input('already_collection') !== null || !empty($request->input('already_collection'))) {

            // This if add in item in already collection
            $checkCollection = Collection::where('id', $request->input('already_collection'))->where('uid', Auth::id())->first();

            if (is_null($checkCollection)) {
                return response()->json(['status' => 'error', 'message' => 'There is not collection'], 422);
            }

            if ($request->input('type') === 'movie') {

                // Before add to collecion check if the movie is already in the collection or not
                $checkIsMovieAlready = Collection_list::where('movie_id', $request->input('id'))->where('collection_id', $checkCollection->id)->first();

                if (!is_null($checkIsMovieAlready)) {
                    return response()->json(['status' => 'error', 'message' => 'The movie is already in collection'], 422);
                }

                // Add to movie to collection
                $addMovieToCollection = new Collection_list();
                $addMovieToCollection->movie_id = $request->input('id');
                $addMovieToCollection->collection_id = $request->input('already_collection');
                $addMovieToCollection->uid = Auth::id();

                $addMovieToCollection->save();

                return response()->json(['status' => 'success', 'message' => 'Successful added ' . $checkAlreadyMovie->m_name . ' to ' . $checkCollection->name . ' collection', 'data' => null]);
            } elseif ($request->input('type') === 'series') {

                // Before add to collecion check if the series is already in the collection or not
                $checkIsSeriesAlready = Collection_list::where('series_id', $request->input('id'))->where('collection_id', $checkCollection->id)->first();

                if (!is_null($checkIsSeriesAlready)) {
                    return response()->json(['status' => 'error', 'message' => 'The series_id is already in collection'], 422);
                }

                // Add to series to collection
                $addSeriesToCollection = new Collection_list();
                $addSeriesToCollection->series_id = $request->input('id');
                $addSeriesToCollection->collection_id = $request->input('already_collection');
                $addSeriesToCollection->uid = Auth::id();

                $addSeriesToCollection->save();

                return response()->json(['status' => 'success', 'message' => 'Successful added ' . $checkAlreadySeries->t_name . ' to ' . $checkCollection->name . ' collection', 'data' => null]);
            } else {

                // Not found
                return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
            }
        }
    }

    /**
     * This function to add new movie or series to collection,
     * Also to create new collection of user set the item in new name collection
     *
     * @return \Illuminate\Http\Response
     *
     */

    public function updateCollection(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|string',
        ]);

        // Check if collection equal to use
        $checkCollection = DB::table('collections')
            ->where('id', $request->input('id'))
            ->where('uid', Auth::id())
            ->first();

        if (is_null($checkCollection)) {
            return response()->json(['status' => 404], 404);
        }
        $edit = Collection::find($request->input('id'));
        $edit->name = $request->input('name');
        $edit->save();

        return response()->json(['status' => 'success', 'message' => 'Successful change collection to ' . $request->input('name')], 200);
    }

    /**
     * Delete collection
     * @return \Illuminate\Http\Response
     *
     */

    public function deleteCollection(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);

        // Check if collection equal to use
        $checkCollection = DB::table('collections')
            ->where('id', $request->input('id'))
            ->where('uid', Auth::id())
            ->first();

        if (is_null($checkCollection)) {
            return response()->json(['status' => 404], 404);
        }
        $delete = Collection::find($request->input('id'));
        $delete->delete();

        return response()->json(['status' => 'success', 'message' => 'Successful delete collection ' . $checkCollection->name], 200);
    }

    /**
     * This function to delete a movie or series from collection,
     *
     * @return \Illuminate\Http\Response
     *
     */

    public function DeleteFromCollection(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
            'type' => 'required|string|max:6',
        ]);

        if ($request->input('type') === 'movie') {

            // Delete movie from collection

            $checkAlreadySeries = Collection_list::where('movie_id', $request->input('id'))->where('uid', Auth::id())->first();

            if (is_null($checkAlreadySeries)) {
                return response()->json(['status' => 404], 404);
            }

            $checkAlreadySeries->delete();

            return response()->json(['status' => 'success', 'message' => 'Successful delete from collection'], 200);
        } elseif ($request->input('type') === 'series') {

            // Delete series from collection

            $checkAlreadySeries = Collection_list::where('series_id', $request->input('id'))->where('uid', Auth::id())->first();

            if (is_null($checkAlreadySeries)) {
                return response()->json(['status' => 404], 404);
            }
            $checkAlreadySeries->delete();

            return response()->json(['status' => 'success', 'message' => 'Successful delete from collection'], 200);
        }
    }
}
