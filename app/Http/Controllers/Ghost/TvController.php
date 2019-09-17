<?php

namespace App\Http\Controllers\Ghost;

use App\Http\Controllers\Controller;
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
                      WHERE category =' . $genres[$key]["id"] . ' AND streaming_status <> 0
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

}
