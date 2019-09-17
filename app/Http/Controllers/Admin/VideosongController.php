<?php

namespace App\Http\Controllers\Admin;

use App\Casts;
use App\Casts_rules;
use App\Http\Controllers\Controller;
use App\VideoSong;
use App\Subtitle;
use App\Tmdb;
use App\Traits\FFmpegTranscoding;
use App\Video;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use \Done\Subtitles\Subtitles;
use Illuminate\Support\Facades\App;

class VideoSongController extends Controller
{
    use FFmpegTranscoding;

    public $number = 0;
    public $name;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin', 'manager']);
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllVideoSongs()
    {
        $getVideoSongs = VideoSong::selectRaw('
                        video_songs.v_id AS id,
                        video_songs.v_name AS name,
                        video_songs.v_poster AS poster,
                        video_songs.v_year AS year,
                        categories.name AS category,
                        video_songs.show,
                        video_songs.v_cloud AS m_cloud,
                        video_songs.created_at,
                        video_songs.updated_at')
            // ->leftJoin('tops', 'tops.videosong_id', '=', 'video_songs.v_id')
            ->leftJoin('categories', 'categories.id', '=', 'video_songs.v_category')
            ->orderBy('video_songs.created_at', 'DESC')
            ->paginate(15);

        // Check if there is no result
        if (empty($getVideoSongs->all())) {
            $getVideoSongs = null;
        }


        return response()->json(
            ['data' => [
                'video_songs' => $getVideoSongs,
            ]],
            200
        );
    }

    /**
     *  Upload To local
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        

        // Upload To Local Or AWS Cloud
        if ($request->cloud_type == 'local') {
            return $this->uploadVideoSongVideoToLocal($request);
        } elseif ($request->cloud_type == 'aws') {
            return $this->uploadVideoSongVideoToAWS($request);
        } else {
            return response()->json(['data' => 'Error cloud not found'], 422);
        }
    }

    /**
     * Upload Subtitle To local
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subtitleUpload(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
        ]);

        $update = VideoSong::find($request->id);

        if (is_null($update)) {
            return response()->json(['status' => 'faild', 'message' => 'There is no VideoSong found'], 404);
        }



        if (!empty($request->file('subtitleUpload'))) {
            if ($update->v_cloud === 'local') {
                foreach ($request->file('subtitleUpload') as $key => $value) {
                    $file = file_get_contents($value);
                    $subtitles = Subtitles::load($file, 'srt');
                    $name = uniqid('subtitle_') . '.vtt';
                    Storage::disk('public')->put('subtitles/' . $update->v_name . '/' . $name, $subtitles->content('vtt'));

                    // Store Data
                    $sub = new Subtitle();
                    $sub->name = '/storage/subtitles/' . $update->v_name . '/' . $name;
                    $sub->language = substr($value->getClientOriginalName(), 0, -4);
                    $sub->videosong_id = $request->id;
                    $sub->save();
                }

                return response()->json(['status' => 'success', 'message' => 'Successful upload subtitles', 'data' => $sub]);
            } elseif ($update->v_cloud === 'aws') {
                foreach ($request->file('subtitleUpload') as $key => $value) {
                    $file = file_get_contents($value);
                    $subtitles = Subtitles::load($file, 'srt');
                    $name = uniqid('subtitle_') . '.vtt';
                    Storage::disk('s3')->put('subtitles/' . $update->v_name . '/' . $name, $subtitles->content('vtt'));

                    // Store Data
                    $sub = new Subtitle();
                    $sub->name =  $update->v_name . '/' . $name;
                    $sub->language = substr($value->getClientOriginalName(), 0, -4);
                    $sub->videosong_id = $request->id;
                    $sub->save();
                }

                return response()->json(['status' => 'success', 'message' => 'Successful upload subtitles', 'data' => $sub]);
            }
        }
    }

    /**
     * Upload To local Custom Function
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customUploadVideoSongDetails(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|regex:/^[a-z0-9 .\-]+$/i',
            // 'overview' => 'required|max:500',
            'video' => 'mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv,webm',
            'video_link' => 'nullable|url',
        ]);


        if (!$request->has('year')) {
            $request->year = 2019;
        }
        // Upload To Local Or AWS Cloud
        if ($request->cloud_type == 'local') {
            return $this->uploadVideoSongCustomInfoToLocal($request);
        } elseif ($request->cloud_type == 'aws') {
            return $this->uploadVideoSongCustomInfoToAWS($request);
        } else {
            return response()->json(['data' => 'Error cloud not found'], 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getVideoSongDetails($id)
    {
        $getVideoSong = DB::table('video_songs')
            ->selectRaw('video_songs.v_id AS id, video_songs.v_name AS name, video_songs.v_poster AS poster, video_songs.v_desc AS overview, video_songs.v_runtime AS runtime, video_songs.v_year AS year, video_songs.v_genre AS genre, video_songs.v_age AS age , video_songs.v_rate AS rate, video_songs.v_backdrop AS backdrop,video_songs.v_youtube AS youtube, video_songs.v_age AS age, video_songs.v_cloud AS cloud, videos.video_url,categories.id AS category')
            ->leftJoin('categories', 'categories.id', '=', 'video_songs.v_category')
            ->join('videos', 'videos.videosong_id', '=', 'video_songs.v_id')
            ->where('video_songs.v_id', $id)
            ->first();

        if (is_null($getVideoSong)) {
            return response()->json(['status' => 'failed', 'message' => 'This VideoSong have not any video, remove it and upload it again.'], 422);
        }

        $getCastOfVideoSong = DB::table(DB::raw('casts'))
            ->select('casts.credit_id', 'casts.c_name AS name', 'casts.c_image AS image', 'casts.c_cloud AS cloud')
            ->join('casts_rules', 'casts_rules.casts_id', '=', 'casts.credit_id')
            ->where('casts_rules.casts_videosongs', $id)
            ->orderBy('c_name', 'ASC')
            ->get();

        $getVideos = DB::table('videos')->where('videosong_id', $id)->get();

        return response()->json([
            'data' => [
                'videosong' => $getVideoSong,
                'cast' => $getCastOfVideoSong,
                'videos' => $getVideos,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        //Validate
        $request->validate([
            'id' => 'required|uuid',
            'runtime' => 'nullable|numeric',
            'rate' => 'nullable|numeric|between:0,99.99',
            'youtube' => 'nullable|max:300',
        ]);

        $update = VideoSong::find($request->id);
        if (!$request->has('year')) {
            $request->year = 2019;
        }

        if (is_null($update)) {
            return response()->json(['status' => 'faild', 'message' => 'There is no VideoSong found'], 404);
        }

        // Update AWS VideoSong

        if ($update->v_cloud == 'local') {
            $update->v_name = $request->name;
            $update->v_year = $request->year;
            $update->v_desc = "";
            $update->v_age = $request->age;
            if ($request->genres !== 'undefined') {
                $update->v_genre = $request->genres;
            }
            $update->v_category = $request->category;
            $update->v_runtime = $request->runtime;
            $update->v_rate = $request->rate;
            $update->v_youtube = $request->youtube;
            $update->v_category = $request->input('category');

            if (!empty($request->file('poster'))) {
                $posterName = str_random(20) . '.jpg';

                // Resize image and upload it to local Storage
                $encodePoster300 = Image::make($request->poster)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodePoster600 = Image::make($request->poster)->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodePosterOriginal = Image::make($request->poster)->encode('jpg');

                $uploadPoster300 = Storage::disk('public')->put('posters/300_' . $posterName, $encodePoster300->__toString());
                $uploadPoster600 = Storage::disk('public')->put('posters/600_' . $posterName, $encodePoster600->__toString());
                $uploadOriginalPoster = Storage::disk('public')->put('posters/original_' . $posterName, $encodePosterOriginal->__toString());

                $update->v_poster = $posterName;
            }
            if (!empty($request->file('backdrop'))) {
                $backdropName = str_random(20) . '.jpg';

                $encodeBackdrop300 = Image::make($request->backdrop)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodeBackdrop600 = Image::make($request->backdrop)->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodeBackdropOriginal = Image::make($request->backdrop)->encode('jpg');

                $uploadBackdrop300 = Storage::disk('public')->put('backdrops/300_' . $backdropName, $encodeBackdrop300->__toString());
                $uploadBackdrop600 = Storage::disk('public')->put('backdrops/600_' . $backdropName, $encodeBackdrop600->__toString());
                $uploadOriginalBackdrop = Storage::disk('public')->put('backdrops/original_' . $backdropName, $encodeBackdropOriginal->__toString());

                $update->v_backdrop = $backdropName;
            }

            // Cast decode
            $casts = json_decode($request->cast);
            // Delete all cast and add new update
            $checkCasts = Casts_rules::where('casts_VideoSongs', $request->id)->first();
            if (!is_null($checkCasts)) {
                $delete = Casts_rules::where('casts_VideoSongs', $request->id)->delete();
                if (!$delete) {
                    return response()->json(['status' => 'failed', 'message' => 'Failed to delete casts'], 422);
                }
            }

            foreach ($casts as $key => $value) {
                $cast = new Casts_rules;
                $cast->casts_id = $value->credit_id;
                $cast->casts_VideoSongs = $request->id;
                $cast->save();
            }


            $update->save();

            // Video decode
            $videos = json_decode($request->videos);
            foreach ($videos as $key => $value) {
                $video = Video::where('v_id', $value->v_id)->first();

                if (empty($value->video_url)) {
                    $video->video_url = null;
                } else {
                    $video->video_url = $value->video_url;
                }
                $video->save();
            }
        } else {

            // Local Upload

            $update->v_name = $request->name;
            $update->v_year = $request->year;
            $update->v_desc = "";
            $update->v_age = $request->age;
            if ($request->genres !== 'undefined') {
                $update->v_genre = $request->genres;
            }
            $update->v_category = $request->category;
            $update->v_runtime = 9;
            $update->v_rate = 9;
            $update->v_youtube = $request->youtube;
            $update->v_category = $request->input('category');

            if (!empty($request->file('poster'))) {
                $posterName = str_random(20) . '.jpg';
                $posterEncode = \Image::make($request->file('poster'))->encode('jpg');
                Storage::disk('s3')->put('posters/' . $posterName, $posterEncode->__toString());
                $update->v_poster = $posterName;
            }
            if (!empty($request->file('backdrop'))) {
                $backdropName = str_random(20) . '.jpg';
                $backdropEncode = \Image::make($request->file('backdrop'))->encode('jpg');
                Storage::disk('s3')->put('backdrops/' . $backdropName, $backdropEncode->__toString());
                $update->v_backdrop = $backdropName;
            }



            // Cast decode
            $casts = json_decode($request->cast);
            // Delete all cast and add new update
            $checkCasts = Casts_rules::where('casts_VideoSongs', $request->id)->first();
            if (!is_null($checkCasts)) {
                $delete = Casts_rules::where('casts_VideoSongs', $request->id)->delete();
                if (!$delete) {
                    return response()->json(['status' => 'failed', 'message' => 'Failed to delete casts'], 422);
                }
            }

            foreach ($casts as $key => $value) {
                $cast = new Casts_rules;
                $cast->casts_id = $value->credit_id;
                $cast->casts_VideoSongs = $request->id;
                $cast->save();
            }


            $update->save();

            // Video decode
            $videos = json_decode($request->videos);
            foreach ($videos as $key => $value) {
                $video = Video::where('v_id', $value->v_id)->first();

                if (empty($value->video_url)) {
                    $video->video_url = null;
                } else {
                    $video->video_url = $value->video_url;
                }
                $video->save();
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Successful update ' . $request->name]);
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteVideoSong(Request $request)
    {
        foreach ($request->list as $value) {
            $delete = VideoSong::find($value['id']);

            if (is_null($delete)) {
                return response()->json(['status' => 'faild', 'message' => 'There is no VideoSong found'], 404);
            }

            if ($delete->v_cloud == 'aws') {
                // Remove video
                Storage::disk('s3')->deleteDirectory('videos/' . $delete->v_name . '/');
                // Remove subtitle
                Storage::disk('s3')->deleteDirectory('subtitles/' . $delete->v_name  . '/');
                $delete->delete();
            } else {
                // Remove video
                Storage::disk('public')->deleteDirectory('videos/' . $delete->v_name . '/');
                // Remove subtitle
                Storage::disk('public')->deleteDirectory('subtitles/' . $delete->v_name . '/');
                $delete->delete();
            }
        }



        return response()->json(['status' => 'success', 'message' => 'successful delete VideoSongs']);
    }

    /**
     * Search VideoSongs by name or id
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function searchVideoSong(Request $request)
    {
        $request->validate([
            'query' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
        ]);

        $getVideoSong = VideoSong::selectRaw('
                VideoSongs.v_id AS id,
                VideoSongs.v_name AS name,
                VideoSongs.v_poster AS poster,
                VideoSongs.v_year AS year,
                VideoSongs.v_cloud,
                                categories.name AS category,
                VideoSongs.show,
                VideoSongs.created_at,
                VideoSongs.updated_at')
            ->leftJoin('categories', 'categories.id', '=', 'VideoSongs.v_category')
            // ->leftJoin('tops', 'tops.VideoSong_id', '=', 'VideoSongs.v_id')
            ->where('v_name', 'like', $request->input('query') . '%')
            ->get();

        if ($getVideoSong->isEmpty()) {
            $getVideoSong = null;
        }

        return response()->json(
            ['data' => [
                'VideoSongs' => $getVideoSong,
            ]],
            200
        );
    }

    /**
     *  Make VideoSong avaliable or unavalibale
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function availableVideoSong(Request $request)
    {
        // Check if there id in episode table

        $array = [];
        foreach ($request->list as $value) {
            $check = VideoSong::find($value['id']);

            if (is_null($check)) {
                return response(['status' => 'failed', 'message' => 'There is no id for this VideoSong on database'], 422);
            }

            if ($check->show) {
                $check->show = 0;
                $check->save();
                array_push($array, ['key' => $value['key'], 'show' => false]);
            } else {
                $check->show = 1;
                $check->save();
                array_push($array, ['key' => $value['key'], 'show' => true]);
            }
        }

        return response(['status' => 'success', 'message' => 'Successful Request', 'list' => $array], 200);
    }


    /**
     * Upload Info Of VideoSong To Local Cloud
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadVideoSongTmdbInfoToLocal($request)
    {

        // Check if there is api of VideoSongdb in config file
        $getApi = Tmdb::find(1);

        if ($getApi->api === null || empty($getApi->api)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no api key in config'], 422);
        }


        $client = new \GuzzleHttp\Client();

        // Get details from theVideoSongdb
        $detils = 'https://api.theVideoSongdb.org/3/VideoSong/' . $request->id . '?api_key=' . $getApi->api . '&language=' . $getApi->language;
        $trailer = 'https://api.theVideoSongdb.org/3/VideoSong/' . $request->id . '/videos?api_key=' . $getApi->api . '&language=' . $getApi->language;

        // Check if there VideoSong or not
        try {
            $res_VideoSongs = $client->get($detils)->getBody();
            $data_VideoSongs = json_decode($res_VideoSongs, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no VideoSong like this id.'], 422);
        }

        // Get video trailer
        $res_trailer = $client->get($trailer)->getBody();
        $data_trailer = json_decode($res_trailer, true);

        // Get the backdrop and poster image from api theVideoSongdb and
        $newPosterName = (!isset($data_VideoSongs['poster_path']) ? 'none' : str_random(20) . '.jpg');
        $poster = (!isset($data_VideoSongs['poster_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w500' . $data_VideoSongs['poster_path'])->getBody());

        $newBackdropName = (!isset($data_VideoSongs['backdrop_path']) ? 'none' : str_random(20) . '.jpg');
        $backdrop = (!isset($data_VideoSongs['backdrop_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w1280' . $data_VideoSongs['backdrop_path'])->getBody());

        // If there no VideoSongs image @return json "Error"
        if ($newPosterName === 'none' || $newBackdropName === 'none') {
            return response()->json(['status' => 'error', 'msg' => 'Please use custom upload, because no image for this VideoSong.']);
        } else {

            // Resize image and upload it to local Storage

            $encodePoster300 = Image::make($poster)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('jpg');
            $encodePoster600 = Image::make($poster)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('jpg');
            $encodePosterOriginal = Image::make($poster)->encode('jpg');

            $uploadPoster300 = Storage::disk('public')->put('posters/300_' . $newPosterName, $encodePoster300->__toString());
            $uploadPoster600 = Storage::disk('public')->put('posters/600_' . $newPosterName, $encodePoster600->__toString());
            $uploadOriginalPoster = Storage::disk('public')->put('posters/original_' . $newPosterName, $encodePosterOriginal->__toString());

            $encodeBackdrop300 = Image::make($backdrop)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('jpg');
            $encodeBackdrop600 = Image::make($backdrop)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('jpg');
            $encodeBackdropOriginal = Image::make($backdrop)->encode('jpg');

            $uploadBackdrop300 = Storage::disk('public')->put('backdrops/300_' . $newBackdropName, $encodeBackdrop300->__toString());
            $uploadBackdrop600 = Storage::disk('public')->put('backdrops/600_' . $newBackdropName, $encodeBackdrop600->__toString());
            $uploadOriginalBackdrop = Storage::disk('public')->put('backdrops/original_' . $newBackdropName, $encodeBackdropOriginal->__toString());
        }

        //Store data
        $store = new VideoSong();
        $store->v_name = $data_VideoSongs['title'];
        $store->v_desc = $data_VideoSongs['overview'];
        $store->v_year = substr($data_VideoSongs['release_date'], 0, 4);
        $store->v_runtime = ($data_VideoSongs['runtime'] === null ? '0' : $data_VideoSongs['runtime']);
        $store->v_rate = ($data_VideoSongs['vote_average'] === null ? '0' : $data_VideoSongs['vote_average']);
        $store->v_youtube = (!isset($data_trailer['results'][0]['key']) ? null : 'https://www.youtube.com/watch?v=' . $data_trailer['results'][0]['key']);
        $store->v_backdrop = "";
        $store->v_poster = "";
        $store->v_age = $request->age;
        $store->v_category = $request->category;
        $store->show = 0;
        $store->v_cloud = 'local';
        $store->v_category = $request->input('category');
        foreach ($data_VideoSongs['genres'] as $key => $value) {
            $genre1[] = $value['name'];
            $genre2 = implode(", ", $genre1);
        }
        $store->v_genre = $genre2;
        $store->save();

        // Get the casts details from TheVideoSongDB and store it in database
        $casts = 'https://api.theVideoSongdb.org/3/VideoSong/' . $request->id . '/credits?api_key=' . $getApi->api . '&language=' . $getApi->language;

        // Check if there cast or not
        try {
            $res_casts = $client->get($casts)->getBody();
            $data_casts = json_decode($res_casts, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no cast'], 422);
        }

        foreach ($data_casts['cast'] as $key => $value) {
            // Break if more than 8 cast
            if ($key > 7) {
                break;
            }

            //Check if image not empty
            if (!empty($value['profile_path'] && !empty($value['credit_id']))) {
                $image_name = str_random(20) . '.jpg';
                $image_content = $client->get('http://image.tmdb.org/t/p/w500/' . $value['profile_path'])->getBody();
                $image_encode = \Image::make($image_content)->widen(200)->encode('jpg');
                $castUplaod = Storage::disk('public')->put('casts/' . $image_name, $image_encode->__toString());

                //If there same cast in db
                $check_cast = Casts::where('credit_id', $value['credit_id'])->first();
                if ($check_cast) {
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_VideoSongs = $store->v_id;
                    $casts_rule->save();
                } else {
                    $casts_store = new Casts;
                    $casts_store->credit_id = $value['credit_id'];
                    $casts_store->c_name = $value['name'];
                    $casts_store->c_image = '/storage/casts/' . $image_name;
                    $casts_store->c_cloud = 'local';
                    $casts_store->save();
                    //Casts rules
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_VideoSongs = $store->v_id;
                    $casts_rule->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Successful store VideoSong details in database', 'id' => $store->v_id], 200);
    }


    public function uploadVideoSongTmdbInfoToAWS($request)
    {

        // Check if there is api of VideoSongdb in config file
        $getApi = Tmdb::find(1);
        if ($getApi->api === null || empty($getApi->api)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no api key in config'], 422);
        }

        $client = new \GuzzleHttp\Client();

        // Get details from theVideoSongdb
        $detils = 'https://api.theVideoSongdb.org/3/VideoSong/' . $request->id . '?api_key=' . $getApi->api  . '&language=' . $getApi->language;
        $trailer = 'https://api.theVideoSongdb.org/3/VideoSong/' . $request->id . '/videos?api_key=' . $getApi->api  . '&language=' . $getApi->language;

        // Check if there VideoSong or not
        try {
            $res_VideoSongs = $client->get($detils)->getBody();
            $data_VideoSongs = json_decode($res_VideoSongs, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no VideoSong like this id.'], 422);
        }
        // Get video trailer
        $res_trailer = $client->get($trailer)->getBody();
        $data_trailer = json_decode($res_trailer, true);
        // Get the backdrop and poster image from api theVideoSongdb and
        $name_poster = (!isset($data_VideoSongs['poster_path']) ? 'none' : str_random(20) . '.jpg');
        $poster = (!isset($data_VideoSongs['poster_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w500' . $data_VideoSongs['poster_path'])->getBody());

        $name_backdrop = (!isset($data_VideoSongs['backdrop_path']) ? 'none' : str_random(20) . '.jpg');
        $backdrop = (!isset($data_VideoSongs['backdrop_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w1280' . $data_VideoSongs['backdrop_path'])->getBody());

        // If there no VideoSongs image @return json "Error"
        if ($name_poster === 'none' || $name_backdrop === 'none') {
            return response()->json(['status' => 'error', 'msg' => 'Please use custom upload, because no image for this VideoSong.']);
        } else {
            // Change image size and upload it to S3 Storage
            $img1 = \Image::make($poster)->encode('jpg');
            $img2 = \Image::make($backdrop)->encode('jpg');
            Storage::disk('s3')->put('posters/' . $name_poster, $img1->__toString());
            Storage::disk('s3')->put('backdrops/' . $name_backdrop, $img2->__toString());
        }

        //Store data
        $store = new VideoSong;
        $store->v_name = $data_VideoSongs['title'];
        $store->v_desc = $data_VideoSongs['overview'];
        $store->v_year = substr($data_VideoSongs['release_date'], 0, 4);
        $store->v_runtime = ($data_VideoSongs['runtime'] === null ? '0' : $data_VideoSongs['runtime']);
        $store->v_rate = ($data_VideoSongs['vote_average'] === null ? '0' : $data_VideoSongs['vote_average']);
        $store->v_youtube = (!isset($data_trailer['results'][0]['key']) ? null : 'https://www.youtube.com/watch?v=' . $data_trailer['results'][0]['key']);
        $store->v_backdrop = $name_backdrop;
        $store->v_poster = $name_poster;
        $store->v_age = $request->age;
        $store->v_category = $request->category;
        $store->show = 0;
        $store->v_cloud = 'aws';
        $store->v_category = $request->input('category');

        foreach ($data_VideoSongs['genres'] as $key => $value) {
            $genre1[] = $value['name'];
            $genre2 = implode(", ", $genre1);
        }
        $store->v_genre = $genre2;
        $store->save();

        // Get the casts details from TheVideoSongDB and store it in database
        $casts = 'https://api.theVideoSongdb.org/3/VideoSong/' . $request->id . '/credits?api_key=' . $getApi->api . '&language=' . $getApi->language;
        $res_casts = $client->get($casts)->getBody();
        $data_casts = json_decode($res_casts, true);

        // Check if there cast or not
        try {
            $res_casts = $client->get($casts)->getBody();
            $data_casts = json_decode($res_casts, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no cast'], 422);
        }

        foreach ($data_casts['cast'] as $key => $value) {
            // Break if more than 8 cast
            if ($key > 7) {
                break;
            }

            //Check if image not empty
            if (!empty($value['profile_path'] && !empty($value['credit_id']))) {
                $image_name = str_random(20) . '.jpg';
                $image_content = $client->get('http://image.tmdb.org/t/p/w500/' . $value['profile_path'])->getBody();
                $image_encode = \Image::make($image_content)->widen(200)->encode('jpg');
                Storage::disk('s3')->put('casts/'  . $image_name, $image_encode->__toString());

                //If there same cast in db
                $check_cast = Casts::where('credit_id', $value['credit_id'])->first();
                if ($check_cast) {
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_VideoSongs = $store->v_id;
                    $casts_rule->save();
                } else {
                    $casts_store = new Casts;
                    $casts_store->credit_id = $value['credit_id'];
                    $casts_store->c_name = $value['name'];
                    $casts_store->c_image = $image_name;
                    $casts_store->c_cloud = 'aws';

                    $casts_store->save();
                    //Casts rules
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_VideoSongs = $store->v_id;
                    $casts_rule->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Successful store VideoSong details in database', 'id' => $store->v_id], 200);
    }


    public function uploadVideoSongCustomInfoToLocal($request)
    {


        // Upload images to local
        $newPosterName = str_random(20) . '.jpg';
        //$newBackdropName = str_random(20) . '.jpg';

        // Resize image and upload it to local Storage
         $encodePoster300 = Image::make($request->poster)->resize(300, null, function ($constraint) {
             $constraint->aspectRatio();
         })->encode('jpg');
         $encodePoster600 = Image::make($request->poster)->resize(600, null, function ($constraint) {
             $constraint->aspectRatio();
         })->encode('jpg');
        $encodePosterOriginal = Image::make($request->poster)->encode('jpg');

        $uploadPoster300 = Storage::disk('public')->put('posters/300_' . $newPosterName, $encodePoster300->__toString());
        $uploadPoster600 = Storage::disk('public')->put('posters/600_' . $newPosterName, $encodePoster600->__toString());
        $uploadOriginalPoster = Storage::disk('public')->put('posters/original_' . $newPosterName, $encodePosterOriginal->__toString());

        // $encodeBackdrop300 = Image::make($request->backdrop)->resize(300, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->encode('jpg');
        // $encodeBackdrop600 = Image::make($request->backdrop)->resize(600, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->encode('jpg');
        // $encodeBackdropOriginal = Image::make($request->backdrop)->encode('jpg');

        // $uploadBackdrop300 = Storage::disk('public')->put('backdrops/300_' . $newBackdropName, $encodeBackdrop300->__toString());
        // $uploadBackdrop600 = Storage::disk('public')->put('backdrops/600_' . $newBackdropName, $encodeBackdrop600->__toString());
        // $uploadOriginalBackdrop = Storage::disk('public')->put('backdrops/original_' . $newBackdropName, $encodeBackdropOriginal->__toString());

        //Store data
        $store = new VideoSong;
        $store->v_name = $request->name;
        $store->v_desc = "";
        $store->v_year = "2019"; //$request->year;
        $store->v_runtime = 5;
        $store->v_rate = 5;
        $store->v_youtube = "";
        $store->v_backdrop = "";
        $store->v_poster = $newPosterName;
        $store->v_age = "";
        $store->v_category = $request->category;
        $store->show = 0;
        $store->v_genre = str_replace(",", "-", $request->genres);
        $store->v_cloud = 'local';
        $store->v_category = $request->input('category');

        $store->save();
        // Add casts

        // $casts = [
        //     '1' => [
        //         'id' => str_random(20),
        //         'name' => $request->cast1,
        //         'image' => $request->image1,
        //     ],
        //     '2' => [
        //         'id' => str_random(20),
        //         'name' => $request->cast2,
        //         'image' => $request->image2,
        //     ],
        //     '3' => [
        //         'id' => str_random(20),
        //         'name' => $request->cast3,
        //         'image' => $request->image3,
        //     ],
        //     '4' => [
        //         'id' => str_random(20),
        //         'name' => $request->cast4,
        //         'image' => $request->image4,
        //     ],
        // ];

        // foreach ($casts as $key => $value) {
        //     if (!empty($value['image']) && $value['image'] !== 'undefined') {
        //         $newActorName = str_random(20) . '.jpg';
        //         $img = \Image::make($value['image'])->widen(200)->encode('jpg');
        //         Storage::disk('public')->put('casts/' . $newActorName, $img->__toString());
        //         $casts_store = new Casts();
        //         $casts_store->credit_id = $value['id'];
        //         $casts_store->c_name = $value['name'];
        //         $casts_store->c_image = '/storage/casts/' . $newActorName;
        //         $casts_store->c_cloud = 'local';
        //         $casts_store->save();

        //         $casts_rule = new Casts_rules();
        //         $casts_rule->casts_id = $value['id'];
        //         $casts_rule->casts_VideoSongs = $store->v_id;
        //         $casts_rule->save();
        //     }
        // }

        return response()->json(['status' => 'success', 'message' => 'Successful store VideoSong details in database', 'id' => $store->v_id], 200);
    }


    public function uploadVideoSongCustomInfoToAWS($request)
    {
        // Upload images to s3
         $name_poster = md5($request->file('poster')->getClientOriginalName() . microtime()) . '.jpg';
        // $name_backdrop = md5($request->file('backdrop')->getClientOriginalName() . microtime()) . '.jpg';
        // $img1 = \Image::make($request->poster)->encode('jpg');
        // $img2 = \Image::make($request->backdrop)->encode('jpg');
        // Storage::disk('s3')->put('posters/' . $name_poster, $img1->__toString());
        // Storage::disk('s3')->put('backdrops/' . $nam_backdrop, $img2->__toString());

        $img1 = \Image::make($request->poster)->encode('jpg');
        //$img2 = \Image::make($request->backdrop)->encode('jpg');
        Storage::disk('s3')->put('posters/' . $name_poster, $img1->__toString());


        //Store data
        $store = new VideoSong();
        $store->v_name = $request->name;
        $store->v_desc = "";
        $store->v_year = 2019;
        $store->v_runtime = 5;
        $store->v_rate = 5;
        $store->v_youtube = "";
        $store->v_backdrop = "";
        $store->v_poster = $name_poster;
        $store->v_age = "";
        //$store->v_category = $request->category;
        $store->show = 0;
        $store->v_genre = ""; //str_replace(",", "-", $request->genres);
        $store->v_cloud = 'aws';
        $store->v_category = $request->input('category');

        $store->save();
        // Add casts
        // $casts = [
        //     '1' => [
        //         'id' => str_random(20),
        //         'name' => $request->cast1,
        //         'image' => $request->image1,
        //     ],
        //     '2' => [
        //         'id' => str_random(20),
        //         'name' => $request->cast2,
        //         'image' => $request->image2,
        //     ],
        //     '3' => [
        //         'id' => str_random(20),
        //         'name' => $request->cast3,
        //         'image' => $request->image3,
        //     ],
        //     '4' => [
        //         'id' => str_random(20),
        //         'name' => $request->cast4,
        //         'image' => $request->image4,
        //     ],
        // ];

        // foreach ($casts as $key => $value) {
        //     if (!empty($value['image']) && $value['image'] !== 'undefined') {
        //         $img_name = str_random(20) . '.jpg';
        //         $img = \Image::make($value['image'])->widen(200)->encode('jpg');
        //         Storage::disk('s3')->put('casts/' . $store->v_id . '/' . $img_name, $img->__toString());
        //         $casts_store = new Casts();
        //         $casts_store->credit_id = $value['id'];
        //         $casts_store->c_name = $value['name'];
        //         $casts_store->c_image = $store->v_id . '/' . $img_name;
        //         $casts_store->save();

        //         $casts_rule = new Casts_rules();
        //         $casts_rule->casts_id = $value['id'];
        //         $casts_rule->casts_VideoSongs = $store->v_id;
        //         $casts_rule->save();
        //     }
        // }

        return response()->json(['status' => 'success', 'message' => 'Successful store VideoSong details in database', 'id' => $store->v_id], 200);
    }

    /**
     * Upload Video To Local Cloud
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadVideoSongVideoToLocal($request)
    {
        if ($request->has('video_link')) {

            // Video decode
            $videos = json_decode($request->video_link);

            foreach ($videos as $key => $value) {
                $video = new video();
                $video->video_cloud = 'link';
                $video->video_format = 'link';
                switch ($key) {
                    case 0:
                        $video->resolution = '320';
                        break;
                    case 1:
                        $video->resolution = '480';
                        break;
                    case 2:
                        $video->resolution = '720';
                        break;
                    case 3:
                        $video->resolution = '1080';
                        break;
                    case 4:
                        $video->resolution = '4K';
                        break;
                }
                $video->videosong_id = $request->id;
                if (empty($value)) {
                    $video->video_url = null;
                } else {
                    $video->video_url = $value;
                }
                $video->save();
            }

            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to local', 'id' => $request->id], 200);
        } elseif ($request->has('video')) {
            // Get VideoSong
            $getVideoSong = VideoSong::find($request->id);


            // Decode Persets Json
            $resolution = json_decode($request->resolution, true);

            if ($resolution[0]['Container'] === 'ts') {

                // Create M3U8 File name
                $randomName = str_random(20);
                $newNameM3U8 = $randomName . '.m3u8';

                // Upload video to Storage
                $file = $request->file('video');
                $path = Storage::disk('public')->put('temp', $file);
                $outputPath = 'VideoSongs/' . $getVideoSong->v_name . '/';


                // FFmpegTranscoding Video
                $transcoding = $this->transcodingToHLS($path, $resolution, $outputPath, $randomName, 'Video Convert To HLS Playlist Wait, its take time', $request->tmdb_id);
                
                // Store video data
                if ($transcoding) {
                    $video = new Video();
                    $video->videosong_id = $request->id;
                    $video->resolution = '720';
                    $video->video_url = '/storage/VideoSongs/' . $getVideoSong->v_name . '/' . $newNameM3U8;
                    $video->video_cloud = 'local';
                    $video->video_format = 'hls';

                    $video->save();
                } else {
                    // Error
                    return $transcoding;
                }
            } elseif ($resolution[0]['Container'] === 'mp4') {


                // Upload video to Storage
                $file = $request->file('video');
                $path = Storage::disk('public')->put('temp', $file);

                // Transcode Video To Mp4
                //$transcode = true;
                $transcode = $this->transcodeVideoToMp4($resolution, $request->id, $path, $request->tmdb_id, 'local', $getVideoSong->v_name,false);

                if (!$transcode) {
                    return response()->json(['status' => 'failed', 'message' => 'Failed to transcode video'], 422);
                }
            }

            //   Storage::deleteDirectory('public/temp');

            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to local', 'id' => $request->id], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'There is something error with video'], 422);
        }
    }


    public function uploadVideoSongVideoToAWS($request)
    {        
        
        if ($request->has('video_link')) {

            // Video decode
            $videos = json_decode($request->video_link);

            foreach ($videos as $key => $value) {
                $video = new video();
                $video->video_cloud = 'link';
                $video->video_format = 'link';
                switch ($key) {
                    case 0:
                        $video->resolution = '320';
                        break;
                    case 1:
                        $video->resolution = '480';
                        break;
                    case 2:
                        $video->resolution = '720';
                        break;
                    case 3:
                        $video->resolution = '1080';
                        break;
                    case 4:
                        $video->resolution = '4K';
                        break;
                }
                $video->videosong_id = $request->id;
                if (empty($value)) {
                    $video->video_url = null;
                } else {
                    $video->video_url = $value;
                }
                $video->save();
            }

            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to AWS S3', 'id' => $request->id], 200);
        } elseif ($request->has('video')) {

            // Get VideoSong
            $getVideoSong = VideoSong::find($request->id);


            // Decode Persets Json
            $resolution = json_decode($request->resolution, true);

            if ($resolution[0]['Container'] === 'ts') {

                // Create M3U8 File name
                $randomName = str_random(20);
                $newNameM3U8 = $randomName . '.m3u8';

                // Upload video to Storage
                $file = $request->file('video');
                $path = Storage::disk('public')->put('temp', $file);
                $outputPath = 'VideoSongs/' . $getVideoSong->v_name . '/';

                // FFmpeg Transcoding Video
                
                $transcoding = $this->transcodingToHLS($path, $resolution, $outputPath, $randomName, 'Video Convert To HLS Playlist Wait, its take time', $request->tmdb_id);
                
                // Store video data
                if ($transcoding) {
                    $video = new Video();
                    $video->video_cloud = 'aws';
                    $video->video_format = 'hls';
                    $video->videosong_id = $request->id;
                    $video->resolution = '720';
                    $video->video_url =  '/VideoSongs/' . $getVideoSong->v_name . '/' . $newNameM3U8;
                    $video->save();


                    $s3 = App::make('aws')->createClient('s3');

                    $s3->uploadDirectory(storage_path('/app/public/VideoSongs/' . $getVideoSong->v_name . '/'), config('aws.private_bucket'), '/VideoSongs/' . $getVideoSong->v_name . '/', []);
                } else {
                    // Error
                    return $transcoding;
                }
            } elseif ($resolution[0]['Container'] === 'mp4') {


                // Upload video to Storage
                $file = $request->file('video');
                $path = Storage::disk('public')->put('temp', $file);

                
                // Transcode Video To Mp4
                $transcode = $this->transcodeVideoToMp4($resolution, $request->id, $path, $request->tmdb_id, 'aws', $getVideoSong->v_name,false);

                if (!$transcode) {
                    return response()->json(['status' => 'failed', 'message' => 'Failed to transcode video'], 422);
                }
            }

            Storage::deleteDirectory('public/temp');
            Storage::deleteDirectory('public/VideoSongs/' . $getVideoSong->v_name . '/');

            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to AWS S3', 'id' => $request->id], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'There is something error with video'], 422);
        }
    }


    public function analysisVideoSong($id)
    {
        if (preg_match('/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}/', $id) !== 1) {
            return response()->json(['status' => 'faild', 'message' => 'Error in id'], 404);
        }

        $check = VideoSong::find($id);

        if (is_null($check)) {
            return response()->json(['status' => 'faild', 'message' => 'There is no VideoSong found'], 404);
        }


        $viewsInDay = DB::table('recently_watcheds')
            ->selectRaw('"VideoSong" AS type, count(recently_watcheds.VideoSong_id) AS number, VideoSongs.v_name AS name, HOUR(recently_watcheds.created_at) AS hour')
            ->join('VideoSongs', 'VideoSongs.v_id', '=', 'recently_watcheds.VideoSong_id')
            ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE() AND VideoSongs.v_id= "' . $id . '"')
            ->groupBy('recently_watcheds.VideoSong_id')
            ->limit(10)
            ->get();

        // Monthly
        $viewsInMonth = DB::table('recently_watcheds')
            ->selectRaw('"VideoSong" AS type, count(recently_watcheds.VideoSong_id) AS number, VideoSongs.v_name AS name, MONTHNAME(recently_watcheds.created_at) AS month')
            ->join('VideoSongs', 'VideoSongs.v_id', '=', 'recently_watcheds.VideoSong_id')
            ->groupBy('recently_watcheds.VideoSong_id')
            ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE() AND VideoSongs.v_id = "' . $id . '"')
            ->limit(10)
            ->get();

        // Yearly

        $viewsInYear = DB::table('recently_watcheds')
            ->selectRaw('"VideoSong" AS type, count(recently_watcheds.VideoSong_id) AS number, VideoSongs.v_name AS name, YEAR(recently_watcheds.created_at) AS year')
            ->join('VideoSongs', 'VideoSongs.v_id', '=', 'recently_watcheds.VideoSong_id')
            ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND VideoSongs.v_id = "' . $id . '"')
            ->groupBy('recently_watcheds.VideoSong_id')
            ->limit(10)
            ->get();

        // Count Like
        $countLikeInDay = DB::table('likes')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE() AND VideoSong_id = "' . $id . '"')
            ->count();

        $countLikeInMonth = DB::table('likes')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 Month) AND CURRENT_DATE() AND VideoSong_id = "' . $id . '"')
            ->count();

        $countLikeInYear = DB::table('likes')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 Year) AND CURRENT_DATE() AND VideoSong_id = "' . $id . '"')
            ->count();


        // Count Favor
        $countFavorInDay = DB::table('collection_lists')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE() AND VideoSong_id = "' . $id . '"')
            ->count();

        $countFavorInMonth = DB::table('collection_lists')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 Month) AND CURRENT_DATE() AND VideoSong_id = "' . $id . '"')
            ->count();

        $countFavorInYear = DB::table('collection_lists')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 Year) AND CURRENT_DATE() AND VideoSong_id = "' . $id . '"')
            ->count();



        $latestViews =  DB::table('recently_watcheds')
            ->selectRaw('users.name AS user_name, users.id AS user_id, recently_watcheds.created_at')
            ->join('VideoSongs', 'VideoSongs.v_id', '=', 'recently_watcheds.VideoSong_id')
            ->join('users', 'users.id', '=', 'recently_watcheds.uid')
            ->whereRaw('VideoSongs.v_id = "' . $id . '"')
            ->orderBy('recently_watcheds.created_at', 'DESC')
            ->limit(20)
            ->get();


        return response()->json([
            'status' => 'success',
            'data' => [
                'views' => [
                    'day' =>  $viewsInDay,
                    'month' => $viewsInMonth,
                    'year' =>  $viewsInYear
                ],
                'like' => [
                    'day' =>  $countLikeInDay,
                    'month' => $countLikeInMonth,
                    'year' =>  $countLikeInYear
                ],
                'favor' => [
                    'day' =>  $countFavorInDay,
                    'month' => $countFavorInMonth,
                    'year' =>  $countFavorInYear
                ],
                'latest_views' => $latestViews,
                'all_views' => DB::table('recently_watcheds')->where('VideoSong_id', $id)->count()
            ]
        ]);
    }
}
