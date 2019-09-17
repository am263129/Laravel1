<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers;
use App\Tv;
use App\Categorie;
use DB;
use App;
use Auth;
use Cookie;

class TvController extends Controller
{
    private $dataLink = null;

    /**
     * This Constructer check if the user is make payment or not if not it will return 404
     *
     * TvController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Helpers::checkUserPayment(Auth::user());

            return $next($request);
        });
    }


    /**
     * Get all channels
     *
     * @return []
     */
    public function getAll()
    {
        $getChannels = Tv::first();
        $getChannelsQueryByGenre = [];

        // Check if there is no channel
        if (is_null($getChannels)) {
            $getChannels = null;
        } else {

            // This array to for loop to determine the type and genre
            $genres = Categorie::where('kind', 'live')->get();

            // Execute query and push it in array
            for ($key = 0; $key < count($genres); $key++) {
                $channelsQuery = DB::select('
                      SELECT
                      *
                      FROM tvs
                      WHERE category ='  . $genres[$key]["id"] . ' AND streaming_status <> 0
                      LIMIT 100');

                array_push($getChannelsQueryByGenre, [
                    'list' => $channelsQuery,
                    'genre' => $genres[$key]['name'],
                    'type' => 'channels',
                ]);
            }


        }
        return response()->json(
            ['data' => [
                'channels' => $getChannelsQueryByGenre
            ]]

        );
    }

    /**
     * Get some channel info
     *
     * @param [uuid] $id
     * @return []
     */
    public function getChannelDetails($id)
    {
        // Validate
        if (preg_match('/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}/', $id) !== 1) {
            return response()->json(['status' => 404], 404);
        }


        $getCurrentChannel = Tv::select('tvs.name', 'tvs.image', 'tvs.created_at', 'categories.name AS category', 'streaming_url')
            ->join('categories', 'categories.id', '=', 'tvs.category')
            ->where('tvs.id', $id)
            ->where('tvs.streaming_status', 1)
            ->first();

        if (is_null($getCurrentChannel)) {
            return response()->json(['status' => 404], 404);
        }


        $suggestion =  Tv::select('tvs.name', 'tvs.image', 'tvs.created_at', 'categories.name AS category', 'streaming_url')
            ->join('categories', 'categories.id', '=', 'tvs.category')
            ->where('tvs.streaming_status', 1)
            ->limit(30)
            ->get();

        if ($suggestion->isEmpty()) {
            $suggestion = null;
        }



        return response()->json(
            ['status' => 'success',
                'data' => [
                    'current_channel' => $getCurrentChannel,
                    'suggestions' => $suggestion,
                ]]
        );
    }


    public function searchChannel(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:15',
        ]);


        $getChannels = DB::select('
                    SELECT
                    *
                    FROM tvs
                    WHERE tvs.name  LIKE "'. $request->input('query') .'%"
            ');

        // Get comain
        return response()->json(
            ['status' => 'success',
                'data' => [
                    'channel_list' => $getChannels
                ]]
        );
    }
}
