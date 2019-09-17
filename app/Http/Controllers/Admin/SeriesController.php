<?php

namespace App\Http\Controllers\Admin;

use App\Casts;
use App\Casts_rules;
use App\Episode;
use App\Events\EventTrigger;
use App\Helpers;
use App\Http\Controllers\Controller;
use App\Series;
use App\Subtitle;
use App\Tmdb;
use App\Traits\FFmpegTranscoding;
use App\Transcoder;
use App\Video;
use Auth;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFMpeg;
use \Done\Subtitles\Subtitles;
use Illuminate\Support\Facades\App;

class SeriesController extends Controller
{
    use FFmpegTranscoding;

    public $number = 0;
    public $name;
    public $WatermarkPosition = null;
    public $tmdb_id;

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
    public function getAllSeries()
    {
        $getSeries = Series::selectRaw('
                        series.t_id AS id,
                        series.t_name AS name,
                        series.t_poster AS poster,
                        series.t_year AS year,
                        series.t_cloud AS cloud,
                        categories.name AS category,
                        series.created_at,
                        series.updated_at,
                        tops.series_id')
            ->leftJoin('tops', 'tops.series_id', '=', 'series.t_id')
            ->leftJoin('categories', 'categories.id', '=', 'series.t_category')
            ->orderBy('series.created_at', 'DESC')
            ->paginate(15);

        // Check if there is no result
        if (empty($getSeries->all())) {
            $getSeries = null;
        }
        return response()->json(
            ['data' => [
                'series' => $getSeries,
            ]],
            200
        );
    }

    /**
     * Upload series details using TMDB API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadSeriesInfoByTmdb(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);


        // Upload To Local Or AWS Cloud
        if ($request->cloud_type == 'local') {

            return $this->uploadSeriesTmdbInfoToLocal($request);

        } else if ($request->cloud_type == 'aws') {

            return $this->uploadSeriesTmdbInfoToAWS($request);

        } else {

            return response()->json(['message' => 'Error cloud not found'], 422);

        }


    }


    /**
     * Upload series details using custom upload
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadSeriesInfoByCustom(Request $request)
    {
        //Validate
        $this->validate($request, [
            'name' => 'required|max:50|regex:/^[a-z0-9 .\-]+$/i',
            'overview' => 'required|max:2000',
            'year' => 'required|numeric|between:0,2030',
            'rate' => 'required|numeric|between:0,99.99',
            'genres' => 'required|max:100',
            'poster' => 'required|mimes:jpeg,jpg,png',
            'backdrop' => 'required|mimes:jpeg,jpg,png',
        ]);


        // Upload To Local Or AWS Cloud
        if ($request->cloud_type == 'local') {

            return $this->uploadSeriesCustomInfoToLocal($request);

        } else if ($request->cloud_type == 'aws') {

            return $this->uploadSeriesCustomInfoToAWS($request);

        } else {

            return response()->json(['message' => 'Error cloud not found'], 422);

        }


    }


    /**
     * Get episode details form TheMovieDB
     *
     * @param Request $request
     * @return void
     */
    public function uploadEpisodeInfoByTmdb(Request $request)
    {
        // Upload To Local Or AWS Cloud
        if ($request->cloud_type == 'local') {

            return $this->uploadEpisodeInfoToLocal($request);

        } else if ($request->cloud_type == 'aws') {

            return $this->uploadEpisodeInfoToAWS($request);

        } else {

            return response()->json(['message' => 'Error cloud not found'], 422);

        }

    }


    /**
     * Custome upload series
     *
     * @param Request $request
     * @return void
     */
    public function uploadEpisodeInfoByCustom(Request $request)
    {

        $request->validate([
            'series_id' => 'required|uuid',
            'season_number' => 'required|numeric|max:1000',
        ]);

        // Upload To Local Or AWS Cloud
        if ($request->cloud_type == 'local') {

            return $this->uploadCustomEpisodeInfoToLocal($request);

        } else if ($request->cloud_type == 'aws') {

            return $this->uploadCustomEpisodeInfoToAWS($request);

        } else {

            return response()->json(['message' => 'Error cloud not found'], 422);

        }

    }


    /**
     * Upload video to local and transcode it
     *
     * @param Request $request
     * @return void
     */
    public function UploadEpisodeVideos(Request $request)
    {

        // Upload To Local Or AWS Cloud
        if ($request->cloud_type == 'local') {

            return $this->uploadEpisodeVideoToLocal($request);

        } else if ($request->cloud_type == 'aws') {

            return $this->uploadEpisodeVideoToAWS($request);

        } else {

            return response()->json(['message' => 'Error cloud not found'], 422);

        }


    }


    /**
     * Convert subtitle srt format to vtt and upload it to local
     *
     * @param Request $request
     * @return void
     */
    public function UploadSubtitleTolocal(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
        ]);

        $checkEpisode = Episode::find($request->id);
        $getSeries = Series::find($checkEpisode->series_id);

        if (is_null($checkEpisode)) {
            return response()->json(['status' => 'faild', 'message' => 'There is no movie found'], 404);
        }


        if (!empty($request->file('subtitleUpload'))) {

            if ($checkEpisode->cloud === 'local') {

                foreach ($request->file('subtitleUpload') as $key => $value) {
                    $file = file_get_contents($value);
                    $subtitles = Subtitles::load($file, 'srt');
                    $name = uniqid('subtitle_') . '.vtt';
                    Storage::disk('public')->put('subtitles/' . $getSeries->t_name . '/' . $checkEpisode->name . '_' . $checkEpisode->season_number . '_' . $checkEpisode->episode_number . '/' . $name, $subtitles->content('vtt'));

                    // Store Data
                    $sub = new Subtitle();
                    $sub->name = '/storage/subtitles/' . $getSeries->t_name . '/' . $checkEpisode->name . '_' . $checkEpisode->season_number . '_' . $checkEpisode->episode_number . '/' . $name;
                    $sub->language = substr($value->getClientOriginalName(), 0, -4);
                    $sub->episode_id = $request->id;
                    $sub->save();
                }

                return response()->json(['status' => 'success', 'message' => 'Successful upload subtitles', 'data' => $sub]);

            } elseif ($checkEpisode->cloud === 'aws') {

                foreach ($request->file('subtitleUpload') as $key => $value) {
                    $file = file_get_contents($value);
                    $subtitles = Subtitles::load($file, 'srt');
                    $name = uniqid('subtitle_') . '.vtt';
                    Storage::disk('s3')->put('subtitles/' . $getSeries->t_name . '/' . $checkEpisode->name . '_' . $checkEpisode->season_number . '_' . $checkEpisode->episode_number . '/' . $name, $subtitles->content('vtt'));
                    // Store Data
                    $sub = new Subtitle();
                    $sub->name = $getSeries->t - name . '/' . $checkEpisode->name . '_' . $checkEpisode->season_number . '_' . $checkEpisode->episode_number . '/' . $name;
                    $sub->language = substr($value->getClientOriginalName(), 0, -4);
                    $sub->episode_id = $request->id;
                    $sub->save();
                }

                return response()->json(['status' => 'success', 'message' => 'Successful upload subtitles', 'data' => $sub]);


            }


        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getAllSeason($id)
    {
        $series = Series::find($id);

        // Check if series is already in database
        if (is_null($series)) {
            return response()->json(['status' => 'success', 'message' => 'There is no series found'], 404);
        }

        // Get season
        $getSeason = DB::table('episodes')
            ->select('episodes.id', 'episodes.episode_number', 'episodes.season_number', 'episodes.name', 'episodes.created_at', 'episodes.updated_at', 'episodes.show', 'episodes.cloud')
            ->join('series', 'series.t_id', '=', 'episodes.series_id')
            ->where('series.t_id', $id)
            ->orderBy('episodes.season_number', 'DESC')
            ->paginate(15);

        // Check if there is no result
        if (empty($getSeason->all())) {
            $getSeason = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'season' => $getSeason,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getSeries($id)
    {
        $getSeries = DB::table('series')
            ->selectRaw('series.t_id AS id, series.t_name AS name, series.t_poster AS poster, series.t_desc AS overview, series.t_year AS year, series.t_genre AS genre, series.t_rate AS rate, series.t_backdrop AS backdrop, series.t_age AS age, series.t_cloud AS cloud, series.t_category AS category')
            ->where('t_id', $id)
            ->first();

        if (is_null($getSeries)) {
            abort(404);
        }

        $getAllCasts = Casts::select('credit_id AS new_credit_id', 'c_image AS image', 'c_name AS name', 'c_cloud AS cloud')->orderBy('c_name', 'ASC')->get();
        $getCastOfSeries = DB::table(DB::raw('casts'))
            ->select('casts.credit_id', 'casts.c_name AS name', 'casts.c_image AS image', 'c_cloud AS cloud')
            ->join('casts_rules', 'casts_rules.casts_id', '=', 'casts.credit_id')
            ->where('casts_rules.casts_series', $id)
            ->orderBy('c_name', 'ASC')
            ->get();

        return response()->json([
            'data' => [
                'series' => $getSeries,
                'cast' => $getCastOfSeries,
                'all_cast' => $getAllCasts,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Validate
        $request->validate([
            'id' => 'required|uuid',
            'name' => 'nullable|max:40',
            'year' => 'nullable|numeric',
            'runtime' => 'nullable|numeric',
            'rate' => 'nullable|numeric|between:0,99.99',
            'category' => 'nullable|numeric',
        ]);

        $update = Series::find($request->id);

        if (is_null($update)) {
            abort(404);
        }


        $update->t_name = $request->name;
        $update->t_year = $request->year;
        $update->t_desc = $request->overview;
        $update->t_category = $request->category;
        if ($request->genres !== 'undefined') {
            $update->t_genre = $request->genres;
        }
        $update->t_rate = $request->rate;


        if ($update->t_cloud == 'local') {

            if (!empty($request->file('poster'))) {
                $newPosterName = str_random(20) . '.jpg';

                // Resize image and upload it to local Storage
                $encodePoster300 = Image::make($request->file('poster'))->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodePoster600 = Image::make($request->file('poster'))->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodePosterOriginal = Image::make($request->file('poster'))->encode('jpg');

                $uploadPoster300 = Storage::disk('public')->put('posters/300_' . $newPosterName, $encodePoster300->__toString());
                $uploadPoster600 = Storage::disk('public')->put('posters/600_' . $newPosterName, $encodePoster600->__toString());
                $uploadOriginalPoster = Storage::disk('public')->put('posters/original_' . $newPosterName, $encodePosterOriginal->__toString());

                $update->t_poster = $newPosterName;

            }
            if (!empty($request->file('backdrop'))) {
                $newBackdropName = str_random(20) . '.jpg';

                $encodeBackdrop300 = Image::make($request->file('backdrop'))->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodeBackdrop600 = Image::make($request->file('backdrop'))->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodeBackdropOriginal = Image::make($request->file('backdrop'))->encode('jpg');

                $uploadBackdrop300 = Storage::disk('public')->put('backdrops/300_' . $newBackdropName, $encodeBackdrop300->__toString());
                $uploadBackdrop600 = Storage::disk('public')->put('backdrops/600_' . $newBackdropName, $encodeBackdrop600->__toString());
                $uploadOriginalBackdrop = Storage::disk('public')->put('backdrops/original_' . $newBackdropName, $encodeBackdropOriginal->__toString());

                $update->t_backdrop = $newBackdropName;

            }


        } elseif ($update->t_cloud == 'aws') {

            if (!empty($request->file('poster'))) {
                $posterName = str_random(20) . '.jpg';
                $backdropEncode = \Image::make($request->file('poster'))->encode('jpg');
                Storage::disk('s3')->put('posters/' . $posterName, $backdropEncode->__toString());
                $update->t_poster = $posterName;
            }
            if (!empty($request->file('backdrop'))) {
                $backdropName = str_random(20) . '.jpg';
                $backdropEncode = \Image::make($request->file('backdrop'))->encode('jpg');
                Storage::disk('s3')->put('backdrops/' . $backdropName, $backdropEncode->__toString());
                $update->t_backdrop = $backdropName;
            }
        }


        // Cast decode
        $casts = json_decode($request->cast);
        // Delete all cast and add new update
        $checkCasts = Casts_rules::where('casts_series', $request->id)->first();
        if (!is_null($checkCasts)) {
            $delete = Casts_rules::where('casts_series', $request->id)->delete();
            if (!$delete) {
                return response()->json(['status' => 'failed', 'message' => 'Failed to delete casts'], 422);

            }
        }

        foreach ($casts as $key => $value) {
            $cast = new Casts_rules;
            $cast->casts_id = $value->credit_id;
            $cast->casts_series = $request->id;
            $cast->save();
        }
        $update->save();


        return response()->json(['status' => 'success', 'message' => 'Successful update ' . $request->name]);
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSeries($id)
    {
        $delete = Series::find($id);

        if (is_null($delete)) {
            return response()->json(['status' => 'faild', 'message' => 'There is no series found'], 404);
        }


        if ($delete->t_cloud == 'aws') {
            // Remove video
            Storage::disk('s3')->deleteDirectory('videos/' . $delete->t_name . '/');
            // Remove subtitle
            Storage::disk('s3')->deleteDirectory('subtitles/' . $delete->t_name . '/');

            $delete->delete();
        } else {
            // Remove video
            Storage::disk('public')->deleteDirectory('videos/' . $delete->t_name . '/');
            // Remove subtitle
            Storage::disk('public')->deleteDirectory('subtitles/' . $delete->t_name . '/');

            $delete->delete();
        }


        return response()->json(['status' => 'success', 'message' => 'successful delete ' . $delete->t_name]);
    }

    /**
     * Search movies by name or id
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function searchSeries(Request $request)
    {
        $request->validate([
            'query' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
        ]);

        $getSeries = series::selectRaw('
                series.t_id AS id,
                series.t_name AS name,
                series.t_poster AS poster,
                series.t_year AS year,
                series.t_cloud AS cloud,
                series.created_at,
                series.updated_at,
                tops.series_id,
                                categories.name AS category')
            ->leftJoin('categories', 'categories.id', '=', 'series.t_category')
            ->leftJoin('tops', 'tops.series_id', '=', 'series.t_id')
            ->where('series.t_name', 'like', $request->input('query') . '%')
            ->get();

        if ($getSeries->isEmpty()) {
            $getSeries = null;
        }

        return response()->json(
            ['data' => [
                'series' => $getSeries,
            ]],
            200
        );
    }

    /**
     * Delete episode
     *
     * @param [type] $id
     * @return void
     */
    public function deleteEpisode(Request $request)
    {

        foreach ($request->list as $value) {

            $delete = Episode::join('series', 'series.t_id', '=', 'episodes.series_id')->where('episodes.id', $value['id'])->first();

            if (is_null($delete)) {
                return response()->json(['status' => 'faild', 'message' => 'There is no epsiode found'], 404);
            }

            if ($delete->t_cloud == 'aws') {
                // Remove video
                Storage::disk('s3_private')->deleteDirectory('series/' . $delete->series_id . '/' .  'season_' . $delete->season_number . '_' . $delete->episode_number);

                $delete->delete();
            } else {
                // Remove video
                Storage::disk('public')->deleteDirectory('series/' . $delete->series_id . '/' .  'season_' . $delete->season_number . '_' . $delete->episode_number);
                // Remove subtitle

                $delete->delete();
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Successful delete'], 200);

    }

    /**
     *  Make episode avaliable or unavalibale
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function availableEpisode(Request $request)
    {

        $array = [];
        foreach ($request->list as $value) {

            $check = Episode::find($value['id']);

            if (is_null($check)) {
                return response(['status' => 'failed', 'message' => 'There is no id for this movie on database'], 422);
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
     * Get episode details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEpisodeDetails($id)
    {

        $check = Episode::find($id);

        if (is_null($check)) {
            return response()->json(['status' => 'failed', 'message' => 'There is episode with this id'], 422);
        }

        $get = DB::table('episodes')
            ->select('episodes.id', 'episodes.name', 'episodes.overview', 'episodes.backdrop', 'episodes.episode_number', 'episodes.cloud', 'episodes.season_number', 'videos.video_url')
            ->join('videos', 'videos.episode_id', '=', 'episodes.id')
            ->where('episodes.id', $id)
            ->first();

        if (is_null($get)) {
            return response()->json(['status' => 'failed', 'message' => 'This episode have not any video, remove it and upload it again.'], 422);
        }

        $getVideos = DB::table('videos')->where('episode_id', $id)->get();

        return response()->json(['status' => 'success', 'data' => [
            'name' => $get->name,
            'overview' => $get->overview,
            'backdrop' => $get->backdrop,
            'episode_number' => $get->episode_number,
            'season_number' => $get->season_number,
            'cloud' => $get->cloud,
            'video_url' => $get->video_url,
            'videos' => $getVideos,

        ]], 200);

    }

    /**
     *  Update Episode
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function updateEpisodeDetails(Request $request)
    {
        //Validate
        $request->validate([
            'id' => 'required|uuid',
            'name' => 'nullable|max:40',
            'overview' => 'nullable|string|max:2000',
        ]);

        $check = Episode::find($request->input('id'));

        if (is_null($check)) {
            return response()->json(['status' => 'failed', 'message' => 'There is episode with this id'], 422);
        }

        $check->name = $request->input('name');
        $check->episode_number = $request->input('episode_number');
        $check->season_number = $request->input('season_number');
        $check->overview = $request->input('overview');


        if ($check->cloud == 'aws') {

            // Upload image if there
            if (!empty($request->file('backdrop'))) {
                $backdropName = str_random(20) . '.jpg';
                $backdropEncode = \Image::make($request->file('backdrop'))->encode('jpg');
                Storage::disk('s3')->put('backdrops/' . $backdropName, $backdropEncode->__toString());
                $check->backdrop = $backdropName;
            }

            $check->save();

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

        } elseif ($check->cloud == 'local') {

            // Upload image if there
            if (!empty($request->file('backdrop'))) {
                $newBackdropName = str_random(20) . '.jpg';

                $encodeBackdrop300 = Image::make($request->file('backdrop'))->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodeBackdrop600 = Image::make($request->file('backdrop'))->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodeBackdropOriginal = Image::make($request->file('backdrop'))->encode('jpg');

                $uploadBackdrop300 = Storage::disk('public')->put('backdrops/300_' . $newBackdropName, $encodeBackdrop300->__toString());
                $uploadBackdrop600 = Storage::disk('public')->put('backdrops/600_' . $newBackdropName, $encodeBackdrop600->__toString());
                $uploadOriginalBackdrop = Storage::disk('public')->put('backdrops/original_' . $newBackdropName, $encodeBackdropOriginal->__toString());

                $check->backdrop = $newBackdropName;

            }

            $check->save();

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
     * Upload Series From TMDB To Local
     *
     * @param $request
     */
    public function uploadSeriesTmdbInfoToLocal($request)
    {

        // Check if there is api of moviedb in config file
        $getApi = Tmdb::find(1);
        if ($getApi->api === null || empty($getApi->api)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no api key in config'], 422);
        }

        $client = new \GuzzleHttp\Client();

        // Get All Series Details From Themoviedb Api
        $details = 'https://api.themoviedb.org/3/tv/' . $request->input('id') . '?api_key=' . $getApi->api . '&language=' . $getApi->language;

        try {
            $res_series = $client->get($details)->getBody();
            $data_series = json_decode($res_series, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no series like this id.'], 422);
        }

        // Get the backdrop and poster image from api themoviedb and
        $newPosterName = (!isset($data_series['poster_path']) ? 'none' : str_random(20) . '.jpg');
        $poster = (!isset($data_series['poster_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w500/' . $data_series['poster_path'])->getBody());

        $newBackdropName = (!isset($data_series['backdrop_path']) ? 'none' : str_random(20) . '.jpg');
        $backdrop = (!isset($data_series['backdrop_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w1280/' . $data_series['backdrop_path'])->getBody());

        // If there no movies image @return json "Error"
        if ($newPosterName === 'none' || $newBackdropName === 'none') {
            return response()->json(['status' => 'failed', 'message' => 'Please use custom upload, because there is no poster or backdrop for this series'], 422);
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

        // Store data
        $store = new Series();
        $store->t_moviedbid = $request->input('id');
        $store->t_age = $request->input('age');
        $store->t_name = $data_series['original_name'];
        $store->t_desc = $data_series['overview'];
        $store->t_year = substr($data_series['first_air_date'], 0, 4);
        $store->t_rate = $data_series['vote_average'];
        $store->t_backdrop = $newBackdropName;
        $store->t_poster = $newPosterName;
        $store->t_cloud = 'local';
        $store->t_category = $request->input('category');
        foreach ($data_series['genres'] as $key => $value) {
            $genre1[] = $value['name'];
            $genre2 = implode(", ", $genre1);
        }
        $store->t_genre = $genre2;
        $store->save();

        // Get the casts details from TheMovieDB and store it in database
        $casts = 'https://api.themoviedb.org/3/tv/' . $request->input('id') . '/credits?api_key=' . $getApi->api . '&language=' . $getApi->language;

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
                Storage::disk('public')->put('casts/' . $image_name, $image_encode->__toString());

                //If there same cast in db
                $check_cast = Casts::where('credit_id', $value['credit_id'])->first();
                if (!is_null($check_cast)) {
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_series = $store->t_id;
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
                    $casts_rule->casts_series = $store->t_id;
                    $casts_rule->save();
                }
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Successful store series details in database', 'id' => $store->t_id], 200);


    }

    /**
     * Upload Series From TMDB To AWS
     *
     * @param $request
     */
    public function uploadSeriesTmdbInfoToAWS($request)
    {
        // Check if there is api of moviedb in config file
        $getApi = Tmdb::find(1);
        if ($getApi->api === null || empty($getApi->api)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no api key in config'], 422);
        }


        $token = config('app.api');
        $client = new \GuzzleHttp\Client();

        // Get All Series Details From Themoviedb Api
        $details = 'https://api.themoviedb.org/3/tv/' . $request->input('id') . '?api_key=' . $getApi->api . '&language=' . $getApi->language;

        try {
            $res_series = $client->get($details)->getBody();
            $data_series = json_decode($res_series, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no series like this id.'], 422);
        }

        // Get the backdrop and poster image from api themoviedb and
        $name_poster = (!isset($data_series['poster_path']) ? 'none' : str_random(20) . '.jpg');
        $poster = (!isset($data_series['poster_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w500/' . $data_series['poster_path'])->getBody());

        $name_backdrop = (!isset($data_series['backdrop_path']) ? 'none' : str_random(20) . '.jpg');
        $backdrop = (!isset($data_series['backdrop_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w1280/' . $data_series['backdrop_path'])->getBody());

        // If there no movies image @return json "Error"
        if ($name_poster === 'none' || $name_backdrop === 'none') {
            return response()->json(['status' => 'failed', 'message' => 'Please use custom upload, because there is no poster or backdrop for this series'], 422);
        } else {
            // Change image size and upload it to S3 Storage
            $img1 = \Image::make($poster)->encode('jpg');
            $img2 = \Image::make($backdrop)->encode('jpg');
            Storage::disk('s3')->put('posters/' . $name_poster, $img1->__toString());
            Storage::disk('s3')->put('backdrops/' . $name_backdrop, $img2->__toString());
        }

        // Store data
        $store = new Series;
        $store->t_moviedbid = $request->input('id');
        $store->t_age = $request->input('age');
        $store->t_name = $data_series['original_name'];
        $store->t_desc = $data_series['overview'];
        $store->t_year = substr($data_series['first_air_date'], 0, 4);
        $store->t_rate = $data_series['vote_average'];
        $store->t_backdrop = $name_backdrop;
        $store->t_poster = $name_poster;
        $store->t_cloud = 'aws';
        $store->t_category = $request->input('category');
        foreach ($data_series['genres'] as $key => $value) {
            $genre1[] = $value['name'];
            $genre2 = implode(", ", $genre1);
        }
        $store->t_genre = $genre2;
        $store->save();

        // Get the casts details from TheMovieDB and store it in database
        $casts = 'https://api.themoviedb.org/3/tv/' . $request->input('id') . '/credits?api_key=' . $getApi->api . '&language=' . $getApi->language;

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
                Storage::disk('s3')->put('casts/' . $store->t_id . '/' . $image_name, $image_encode->__toString());

                //If there same cast in db
                $check_cast = Casts::where('credit_id', $value['credit_id'])->first();
                if (!is_null($check_cast)) {
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_series = $store->t_id;
                    $casts_rule->save();
                } else {
                    $casts_store = new Casts;
                    $casts_store->credit_id = $value['credit_id'];
                    $casts_store->c_name = $value['name'];
                    $casts_store->c_image = $store->t_id . '/' . $image_name;
                    $casts_store->c_cloud = 'aws';

                    $casts_store->save();
                    //Casts rules
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_series = $store->t_id;
                    $casts_rule->save();
                }
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Successful store series details in database', 'id' => $store->t_id], 200);
    }


    /**
     * Custom Upload Series To Local
     *
     * @param $request
     */
    public function uploadSeriesCustomInfoToLocal($request)
    {

        // Upload images to local
        $newPosterName = str_random(20) . '.jpg';
        $newBackdropName = str_random(20) . '.jpg';

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

        $encodeBackdrop300 = Image::make($request->backdrop)->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');
        $encodeBackdrop600 = Image::make($request->backdrop)->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');
        $encodeBackdropOriginal = Image::make($request->backdrop)->encode('jpg');

        $uploadBackdrop300 = Storage::disk('public')->put('backdrops/300_' . $newBackdropName, $encodeBackdrop300->__toString());
        $uploadBackdrop600 = Storage::disk('public')->put('backdrops/600_' . $newBackdropName, $encodeBackdrop600->__toString());
        $uploadOriginalBackdrop = Storage::disk('public')->put('backdrops/original_' . $newBackdropName, $encodeBackdropOriginal->__toString());

        //Store data
        $store = new Series;
        $store->t_moviedbid = 0;
        $store->t_name = $request->name;
        $store->t_desc = $request->overview;
        $store->t_year = $request->year;
        $store->t_rate = $request->rate;
        $store->t_backdrop = $newBackdropName;
        $store->t_poster = $newPosterName;
        $store->t_age = $request->age;
        $store->t_genre = str_replace(",", "-", $request->genres);
        $store->t_cloud = 'local';
        $store->t_category = $request->category;
        $store->save();

        $casts = [
            '1' => [
                'id' => str_random(20),
                'name' => $request->cast1,
                'image' => $request->image1,
            ],
            '2' => [
                'id' => str_random(20),
                'name' => $request->cast2,
                'image' => $request->image2,
            ],
            '3' => [
                'id' => str_random(20),
                'name' => $request->cast3,
                'image' => $request->image3,
            ],
            '4' => [
                'id' => str_random(20),
                'name' => $request->cast4,
                'image' => $request->image4,
            ],
        ];

        foreach ($casts as $key => $value) {
            if (!empty($value['image']) && $value['image'] !== 'undefined') {
                $newImgName = str_random(20) . '.jpg';
                $img = \Image::make($value['image'])->widen(200)->encode('jpg');
                Storage::disk('public')->put('casts/' . $newImgName, $img->__toString());
                $casts_store = new Casts();
                $casts_store->credit_id = $value['id'];
                $casts_store->c_name = $value['name'];
                $casts_store->c_image = '/storage/casts/' . $newImgName;
                $casts_store->c_cloud = 'local';
                $casts_store->save();

                $casts_rule = new Casts_rules();
                $casts_rule->casts_id = $value['id'];
                $casts_rule->casts_movies = $store->t_id;
                $casts_rule->save();
            }
        }

        return response()->json(['status' => 'success', 'msg' => 'Successful upload ' . $request->name]);
    }

    /**
     * Upload Series From Custom To AWS
     *
     * @param $request
     */
    public function uploadSeriesCustomInfoToAWS($request)
    {

        // Upload images to s3
        $posterName = md5($request->file('poster')->getClientOriginalName() . microtime()) . '.jpg';
        $backdropName = md5($request->file('backdrop')->getClientOriginalName() . microtime()) . '.jpg';
        $posterImage = \Image::make($request->poster)->encode('jpg');
        $backdropImage = \Image::make($request->backdrop)->encode('jpg');
        Storage::disk('s3')->put('posters/' . $posterName, $posterImage->__toString());
        Storage::disk('s3')->put('backdrops/' . $backdropName, $backdropImage->__toString());

        //Store data
        $store = new Series;
        $store->t_moviedbid = 0;
        $store->t_name = $request->name;
        $store->t_desc = $request->overview;
        $store->t_year = $request->year;
        $store->t_rate = $request->rate;
        $store->t_backdrop = $backdropName;
        $store->t_poster = $posterName;
        $store->t_age = $request->age;
        $store->t_genre = str_replace(",", "-", $request->genres);
        $store->t_cloud = 'aws';
        $store->t_category = $request->category;
        $store->save();

        $casts = [
            '1' => [
                'id' => str_random(20),
                'name' => $request->cast1,
                'image' => $request->image1,
            ],
            '2' => [
                'id' => str_random(20),
                'name' => $request->cast2,
                'image' => $request->image2,
            ],
            '3' => [
                'id' => str_random(20),
                'name' => $request->cast3,
                'image' => $request->image3,
            ],
            '4' => [
                'id' => str_random(20),
                'name' => $request->cast4,
                'image' => $request->image4,
            ],
        ];

        foreach ($casts as $key => $value) {
            if (!empty($value['image']) && $value['image'] !== 'undefined') {
                $img_name = str_random(20) . '.jpg';
                $img = \Image::make($value['image'])->widen(200)->encode('jpg');
                Storage::disk('s3')->put('casts/' . $store->t_id . '/' . $img_name, $img->__toString());
                $casts_store = new Casts();
                $casts_store->credit_id = $value['id'];
                $casts_store->c_name = $value['name'];
                $casts_store->c_image = $store->t_id . '/' . $img_name;
                $casts_store->c_cloud = 'aws';
                $casts_store->save();

                $casts_rule = new Casts_rules();
                $casts_rule->casts_id = $value['id'];
                $casts_rule->casts_movies = $store->t_id;
                $casts_rule->save();
            }
        }

        return response()->json(['status' => 'success', 'msg' => 'Successful upload ' . $request->name]);
    }


    public function uploadEpisodeInfoToLocal($request)
    {
        // Check if there is api of moviedb in config file
        $getApi = Tmdb::find(1);
        if ($getApi->api === null || empty($getApi->api)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no api key in config'], 422);
        }

        $request->validate([
            'series_id' => 'required|uuid',
            'season_number' => 'required|numeric|max:1000',
        ]);

        // Check if series is exist already
        $checkAlreadySeries = Series::where('t_id', $request->input('series_id'))->first();

        // Throw error if there is no series equal id in database
        if (is_null($checkAlreadySeries)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no series like this id.'], 422);
        }

        // Get api from config file
        $client = new \GuzzleHttp\Client();

        // Get all episode details from themoviedb Api
        // https://developers.themoviedb.org/3/tv-episodes/get-tv-episode-details

        $episodeList = json_decode($request->input('episode_number'));

        // Add one Or Multi Episode
        $EpisodeListIdStore = [];
        foreach ($episodeList as $key => $value) {

            $tmdbLink = 'https://api.themoviedb.org/3/tv/' . $checkAlreadySeries->t_moviedbid . '/season/' . $request->input('season_number') . '/episode/' . $value . '?api_key=' . $getApi->api . '&language=' . $getApi->language;

            try {
                $getEpisodeData = $client->get($tmdbLink)->getBody();
                $episodeData = json_decode($getEpisodeData, true);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                return response()->json(['status' => 'failed', 'message' => 'There is no episode ' . $value . ' in TMDB'], 422);
            }

            // Check if there is episode image or not
            if (!isset($episodeData['still_path'])) {
                return response()->json(['status' => 'error', 'message' => 'There is no episode, upload it from custom upload'], 422);
            }

            // Get image and uplaod it to local
            // The size of episode backdrop is 1000, check more size from
            // https://developers.themoviedb.org/3/getting-started/images

            $backdrop = $client->get('https://image.tmdb.org/t/p/w1280/' . $episodeData['still_path'])->getBody();
            $newBackdropName = str_random(20) . '.jpg';

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

            //Store Episode Data
            $store = new Episode;
            $store->series_id = $request->input('series_id');
            $store->season_number = $request->input('season_number');
            $store->episode_number = $value;
            $store->name = $episodeData['name'];
            $store->overview = $episodeData['overview'];
            $store->backdrop = $newBackdropName;
            $store->show = 0;
            $store->cloud = 'local';
            $store->save();

            $EpisodeListIdStore[] = ['id' => $store->id, 'season_number' => $request->input('season_number'), 'episode_number' => $value, 'episode_name' => $episodeData['name']];
        }

        return response()->json(['status' => 'success', 'message' => 'Successful store episode details in database', 'id' => $EpisodeListIdStore, 'series_name' => $checkAlreadySeries->t_name], 200);
    }


    public function uploadEpisodeInfoToAWS($request)
    {

        // Check if there is api of moviedb in config file
        $getApi = Tmdb::find(1);
        if ($getApi->api === null || empty($getApi->api)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no api key in config'], 422);
        }


        $request->validate([
            'series_id' => 'required|uuid',
            'season_number' => 'required|numeric|max:1000',
        ]);

        // Check if series is exist already
        $checkAlreadySeries = Series::where('t_id', $request->input('series_id'))->first();

        // Throw error if there is no series equal id in database
        if (is_null($checkAlreadySeries)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no series like this id.'], 422);
        }

        // Get api from config file
        $token = config('app.api');
        $client = new \GuzzleHttp\Client();

        // Get all episode details from themoviedb Api
        // https://developers.themoviedb.org/3/tv-episodes/get-tv-episode-details


        $episodeList = json_decode($request->input('episode_number'));

        // Add one Or Multi Episode
        $EpisodeListIdStore = [];
        foreach ($episodeList as $key => $value) {

            $movidedbLink = 'https://api.themoviedb.org/3/tv/' . $checkAlreadySeries->t_moviedbid . '/season/' . $request->input('season_number') . '/episode/' . $value . '?api_key=' . $getApi->api . '&language=' . $getApi->language;

            try {
                $getEpisodeData = $client->get($movidedbLink)->getBody();
                $episodeData = json_decode($getEpisodeData, true);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                return response()->json(['status' => 'failed', 'message' => 'There is no episode ' . $value . ' in TMDB'], 422);
            }

            // Check if there is episode image or not
            if (!isset($episodeData['still_path'])) {
                return response()->json(['status' => 'error', 'message' => 'There is no episode, upload it from custom upload'], 422);
            }

            // Get image and uplaod it to s3
            // The size of episode backdrop is 1000, check more size from
            // https://developers.themoviedb.org/3/getting-started/images

            $backdrop = $client->get('https://image.tmdb.org/t/p/w1280/' . $episodeData['still_path'])->getBody();
            $backdropName = str_random(20) . '.jpg';
            $backdropEncode = \Image::make($backdrop)->encode('jpg');
            Storage::disk('s3')->put('backdrops/' . $backdropName, $backdropEncode->__toString());

            //Store Episode Data
            $store = new Episode;
            $store->series_id = $request->input('series_id');
            $store->season_number = $request->input('season_number');
            $store->episode_number = $value;
            $store->name = $episodeData['name'];
            $store->overview = $episodeData['overview'];
            $store->backdrop = $backdropName;
            $store->show = 0;
            $store->cloud = 'aws';
            $store->save();

            $EpisodeListIdStore[] = ['id' => $store->id, 'season_number' => $request->input('season_number'), 'episode_number' => $value, 'episode_name' => $episodeData['name']];
        }

        return response()->json(['status' => 'success', 'message' => 'Successful store episode details in database', 'id' => $EpisodeListIdStore, 'series_name' => $checkAlreadySeries->t_name], 200);
    }


    public function uploadCustomEpisodeInfoToLocal($request)
    {

        // Check if series is exist already
        $checkAlreadySeries = Series::where('t_id', $request->input('series_id'))->first();

        // Throw error if there is no series equal id in database
        if (is_null($checkAlreadySeries)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no series like this id.'], 422);
        }

        $episodeList = json_decode($request->input('episode_number'));
        $EpisodeListIdStore = [];
        foreach ($request->file('backdrop') as $key => $value) {
            if ($episodeList[$key] == strtok($value->getClientOriginalName(), '.')) {

                $newBackdropName = str_random(20) . '.jpg';
                $encodeBackdrop300 = Image::make($value)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodeBackdrop600 = Image::make($value)->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');
                $encodeBackdropOriginal = Image::make($value)->encode('jpg');

                $uploadBackdrop300 = Storage::disk('public')->put('backdrops/300_' . $newBackdropName, $encodeBackdrop300->__toString());
                $uploadBackdrop600 = Storage::disk('public')->put('backdrops/600_' . $newBackdropName, $encodeBackdrop600->__toString());
                $uploadOriginalBackdrop = Storage::disk('public')->put('backdrops/original_' . $newBackdropName, $encodeBackdropOriginal->__toString());

                //Store Episode Data
                $store = new Episode;
                $store->series_id = $request->input('series_id');
                $store->season_number = $request->input('season_number');
                $store->episode_number = $episodeList[$key];
                $store->backdrop = $newBackdropName;
                $store->show = 0;
                $store->cloud = 'local';
                $store->save();

                $EpisodeListIdStore[] = ['id' => $store->id, 'season_number' => $request->input('season_number'), 'episode_number' => $episodeList[$key], 'episode_name' => null];

            } else {
                return response(['status' => 'failed', 'message' => 'Please check if the image name is same episode number.'], 422);
            }

        }
        return response()->json(['status' => 'success', 'message' => 'Successful store episode details in database', 'id' => $EpisodeListIdStore, 'series_name' => $checkAlreadySeries->t_name], 200);

    }


    public function uploadCustomEpisodeInfoToAWS($request)
    {

        // Check if series is exist already
        $checkAlreadySeries = Series::where('t_id', $request->input('series_id'))->first();

        // Throw error if there is no series equal id in database
        if (is_null($checkAlreadySeries)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no series like this id.'], 422);
        }

        $episodeList = json_decode($request->input('episode_number'));
        $EpisodeListIdStore = [];
        foreach ($request->file('backdrop') as $key => $value) {
            if ($episodeList[$key] == strtok($value->getClientOriginalName(), '.')) {


                $backdropName = str_random(20) . '.jpg';
                $backdropEncode = \Image::make($value)->encode('jpg');
                Storage::disk('s3')->put('backdrops/' . $backdropName, $backdropEncode->__toString());
                //Store Episode Data
                $store = new Episode;
                $store->series_id = $request->input('series_id');
                $store->season_number = $request->input('season_number');
                $store->episode_number = $episodeList[$key];
                $store->backdrop = $backdropName;
                $store->show = 0;
                $store->cloud = 'aws';
                $store->save();

                $EpisodeListIdStore[] = ['id' => $store->id, 'season_number' => $request->input('season_number'), 'episode_number' => $episodeList[$key], 'episode_name' => null];

            } else {
                return response(['status' => 'failed', 'message' => 'Please check if the image name is same episode number.'], 422);
            }

        }

        return response()->json(['status' => 'success', 'message' => 'Successful store episode details in database', 'id' => $EpisodeListIdStore, 'series_name' => $checkAlreadySeries->t_name], 200);

    }


    public function uploadEpisodeVideoToLocal($request)
    {
        // Upload video to local Storage
        $idEpisodeList = json_decode($request->id);
        $listVideoNameAndId = [];


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
                $video->episode_id = $idEpisodeList[0]->id;
                if (empty($value)) {
                    $video->video_url = null;
                } else {
                    $video->video_url = $value;
                }
                $video->save();
            }

            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to local', 'id' => $request->id], 200);

        } elseif ($request->video !== 'undefined' && !empty($request->video)) {

            foreach ($request->file('video') as $videoKey => $videoValue) {
                if ($idEpisodeList[$videoKey]->episode_number == strtok($videoValue->getClientOriginalName(), '.')) {

                    // upload videos episode
                    $path = Storage::disk('public')->put('temp', $videoValue);
                    $listVideoNameAndId[$videoKey]['id'] = $idEpisodeList[$videoKey]->id;
                    $listVideoNameAndId[$videoKey]['path'] = $path;
                    $listVideoNameAndId[$videoKey]['episode_number'] = $idEpisodeList[$videoKey]->episode_number;
                    $listVideoNameAndId[$videoKey]['episode_name'] = $idEpisodeList[$videoKey]->episode_name;
                    $listVideoNameAndId[$videoKey]['season_number'] = $idEpisodeList[$videoKey]->season_number;
                } else {
                    return response(['status' => 'failed', 'message' => 'Please check if the video name is same episode number.'], 422);
                }
            }

            // Decode Persets Json
            $resolution = json_decode($request->resolution, true);


            // If the prests is HLS
            if ($resolution[0]['Container'] === 'ts') {
                foreach ($listVideoNameAndId as $videoKey => $videoValue) {
                    $this->number = 0;

                    // Create M3U8 File name
                    $randomName = str_random(20);
                    $newNameM3U8 = $randomName . '.m3u8';

                    $path_upload = 'series/' . $request->series_id . '/'. 'season_' . $videoValue['season_number'] . '_' . $videoValue['episode_number'] . '/';

                    $transcode = $this->transcodingToHLS($videoValue['path'], $resolution, $path_upload, $randomName, 'Episode ' . $videoValue['episode_number'] . ' is in processing', $request->tmdb_id);

                    if ($transcode) {
                        //Store video data
                        $video = new Video();
                        $video->video_format = 'hls';
                        $video->video_cloud = 'local';
                        $video->episode_id = $videoValue['id'];
                        $video->resolution = '720';
                        $video->video_url = '/storage/' . $path_upload . $newNameM3U8;
                        $video->save();
                    } else {
                        // Throw error
                        return $transcode;
                    }
                }
            } else {


                // if presets is mp4
                $lowBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(700);
                $midBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(1250);
                $highBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(2500);
                $vHighBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(4500);
                $ultraHighBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(14000);

                foreach ($listVideoNameAndId as $videoKey => $videoValue) {


                    // Check if there is watermark
                    if (Transcoder::first()->watermark_url !== null && !empty(Transcoder::first()->watermark_url)) {
                        $this->WatermarkPosition = Helpers::getWatermarkPosition(Transcoder::first()->watermark_position);
                    }

                    $path_upload = 'series/' . $request->series_id . '/'. 'season_' . $videoValue['season_number'] . '_' . $videoValue['episode_number'] . '/';


                    $this->episode_number = $videoValue['episode_number'];
                    $this->tmdb_id = $request->tmdb_id;

                    foreach ($resolution as $key => $value) {

                        if ($value['Resolution'] === '4k') {
                            $this->number = 0;

                            // Progress
                            $ultraHighBitrate->on('progress', function ($converVeryHigh, $ultraHighBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . 'is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });
                            $newNameMP4 = str_random(20) . '.mp4';
                            $converVeryHigh = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('public')
                                ->inFormat($ultraHighBitrate)
                                ->save($path_upload . '/' . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '4k';
                            $video->video_url = '/storage/' . $path_upload . '/' . $newNameMP4;
                            $video->video_cloud = 'local';
                            $video->video_format = 'mp4';
                            $video->save();
                        }

                        if ($value['Resolution'] === '1080') {
                            $this->number = 0;

                            // Progress
                            $vHighBitrate->on('progress', function ($converVeryHigh, $veryHighBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . 'is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });
                            $newNameMP4 = str_random(20) . '.mp4';
                            $converVeryHigh = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('public')
                                ->inFormat($vHighBitrate)
                                ->save($path_upload . '/' . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '1080';
                            $video->video_url = '/storage/' . $path_upload . '/' . $newNameMP4;
                            $video->video_cloud = 'local';
                            $video->video_format = 'mp4';
                            $video->save();
                        }

                        if ($value['Resolution'] === '720') {
                            $this->number = 0;

                            // Progress
                            $highBitrate->on('progress', function ($convertHigh, $highBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . 'is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });

                            $newNameMP4 = str_random(20) . '.mp4';
                            $convertHigh = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('public')
                                ->inFormat($highBitrate)
                                ->save($path_upload . '/' . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '720';
                            $video->video_url = '/storage/' . $path_upload . '/' . $newNameMP4;
                            $video->video_cloud = 'local';
                            $video->video_format = 'mp4';
                            $video->save();
                        }

                        if ($value['Resolution'] === '480') {
                            $this->number = 0;

                            // Progress
                            $midBitrate->on('progress', function ($convertMid, $midBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . 'is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });

                            $newNameMP4 = str_random(20) . '.mp4';
                            $convertMid = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('public')
                                ->inFormat($midBitrate)
                                ->save($path_upload . '/' . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '480';
                            $video->video_url = '/storage/' . $path_upload . '/' . $newNameMP4;
                            $video->video_cloud = 'local';
                            $video->video_format = 'mp4';
                            $video->save();
                        }

                        if ($value['Resolution'] === '320') {
                            $this->number = 0;

                            // Progress
                            $lowBitrate->on('progress', function ($convertLow, $lowBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . 'is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });

                            $newNameMP4 = str_random(20) . '.mp4';
                            $convertLow = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('public')
                                ->inFormat($lowBitrate)
                                ->save($path_upload . '/' . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '320';
                            $video->video_url = '/storage/' . $path_upload . '/' . $newNameMP4;
                            $video->video_cloud = 'local';
                            $video->video_format = 'mp4';
                            $video->save();
                        }
                    }
                }
            }

            Storage::deleteDirectory('public/temp');

            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to aws s3'], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'There is something error with video'], 422);
        }
    }


    public function uploadEpisodeVideoToAWS($request)
    {

        // Upload video to local Storage
        $idEpisodeList = json_decode($request->id);
        $listVideoNameAndId = [];

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
                $video->episode_id = $idEpisodeList[0]->id;
                if (empty($value)) {
                    $video->video_url = null;
                } else {
                    $video->video_url = $value;
                }
                $video->save();
            }

            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to local', 'id' => $request->id], 200);

        } elseif ($request->video !== 'undefined' && !empty($request->video)) {

            foreach ($request->file('video') as $videoKey => $videoValue) {
                if ($idEpisodeList[$videoKey]->episode_number == strtok($videoValue->getClientOriginalName(), '.')) {

                    // upload videos episode
                    $path = Storage::disk('public')->put('temp', $videoValue);
                    $listVideoNameAndId[$videoKey]['path'] = $path;
                    $listVideoNameAndId[$videoKey]['id'] = $idEpisodeList[$videoKey]->id;
                    $listVideoNameAndId[$videoKey]['episode_name'] = $idEpisodeList[$videoKey]->episode_name;
                    $listVideoNameAndId[$videoKey]['episode_number'] = $idEpisodeList[$videoKey]->episode_number;
                    $listVideoNameAndId[$videoKey]['season_number'] = $idEpisodeList[$videoKey]->season_number;
                    // Store type local for each episode
                    $addtype = Episode::find($idEpisodeList[$videoKey]->id);
                    $addtype->save();

                } else {
                    return response(['status' => 'failed', 'message' => 'Please check if the video name is sanme episode number.'], 422);
                }
            }

            // Decode Persets Json
            $resolution = json_decode($request->resolution, true);


            // If the prests is HLS
            if ($resolution[0]['Container'] === 'ts') {
                foreach ($listVideoNameAndId as $videoKey => $videoValue) {
                    $this->number = 0;

                    // Create M3U8 File name
                    $randomName = str_random(20);
                    $newNameM3U8 = $randomName . '.m3u8';


                    $path_upload = 'series/' . $request->series_id . '/' . 'season_' . $videoValue['season_number'] . '_' . $videoValue['episode_number'] . '/';

                    $transcode = $this->transcodingToHLS($videoValue['path'], $resolution, $path_upload, $randomName, 'Episode ' . $videoValue['episode_number'] . ' is in processing', $request->tmdb_id);

                    if ($transcode) {
                        //Store video data
                        $video = new Video();
                        $video->video_format = 'hls';
                        $video->video_cloud = 'aws';
                        $video->episode_id = $videoValue['id'];
                        $video->resolution = '720';
                        $video->video_url =  $path_upload . $newNameM3U8;
                        $video->save();

                        $s3 = App::make('aws')->createClient('s3');

                        $s3->uploadDirectory(storage_path('/app/public/' . $path_upload), config('aws.private_bucket'), $path_upload, []);

                    } else {
                        // Throw error
                        return $transcode;
                    }
                }
            } else {


                // Check if there is watermark
                if (Transcoder::first()->watermark_url !== null && !empty(Transcoder::first()->watermark_url)) {
                    $this->WatermarkPosition = Helpers::getWatermarkPosition(Transcoder::first()->watermark_position);
                }


                // if presets is mp4
                $lowBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(700);
                $midBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(1250);
                $highBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(2500);
                $vHighBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(4500);
                $ultraHighBitrate = (new X264('aac', 'libx264'))->setKiloBitrate(14000);

                foreach ($listVideoNameAndId as $videoKey => $videoValue) {

                    // Re-name Path Name
                    $path_upload = 'series/' . $request->series_id . '/' . 'season_' . $videoValue['season_number'] . '_' . $videoValue['episode_number'] . '/';


                    $this->episode_number = $videoValue['episode_number'];
                    $this->tmdb_id = $request->tmdb_id;

                    foreach ($resolution as $key => $value) {

                        if ($value['Resolution'] === '4k') {
                            $this->number = 0;

                            // Progress
                            $ultraHighBitrate->on('progress', function ($converVeryHigh, $ultraHighBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . ' is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });
                            $newNameMP4 = str_random(20) . '.mp4';
                            $converVeryHigh = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('s3_private')
                                ->inFormat($ultraHighBitrate)
                                ->save($path_upload . '/' . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '4k';
                            $video->video_url =  $path_upload . $newNameMP4;
                            $video->video_cloud = 'aws';
                            $video->video_format = 'mp4';
                            $video->save();
                        }

                        if ($value['Resolution'] === '1080') {
                            $this->number = 0;

                            // Progress
                            $vHighBitrate->on('progress', function ($converVeryHigh, $veryHighBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . ' is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });
                            $newNameMP4 = str_random(20) . '.mp4';
                            $converVeryHigh = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('s3_private')
                                ->inFormat($vHighBitrate)
                                ->save($path_upload . '/' . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '1080';
                            $video->video_url =  $path_upload . $newNameMP4;
                            $video->video_cloud = 'aws';
                            $video->video_format = 'mp4';
                            $video->save();
                        }

                        if ($value['Resolution'] === '720') {
                            $this->number = 0;

                            // Progress
                            $highBitrate->on('progress', function ($convertHigh, $highBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . ' is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });

                            $newNameMP4 = str_random(20) . '.mp4';
                            $convertHigh = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('s3_private')
                                ->inFormat($highBitrate)
                                ->save($path_upload . '/' . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '720';
                            $video->video_url =  $path_upload . $newNameMP4;
                            $video->video_cloud = 'aws';
                            $video->video_format = 'mp4';
                            $video->save();
                        }

                        if ($value['Resolution'] === '480') {
                            $this->number = 0;

                            // Progress
                            $midBitrate->on('progress', function ($convertMid, $midBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . ' is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });

                            $newNameMP4 = str_random(20) . '.mp4';
                            $convertMid = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('s3_private')
                                ->inFormat($midBitrate)
                                ->save($path_upload . '/' . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '480';
                            $video->video_url =  $path_upload . $newNameMP4;
                            $video->video_cloud = 'aws';
                            $video->video_format = 'mp4';
                            $video->save();
                        }

                        if ($value['Resolution'] === '320') {
                            $this->number = 0;

                            // Progress
                            $lowBitrate->on('progress', function ($convertLow, $lowBitrate, $percentage) {
                                if (ctype_digit(strval($percentage))) {
                                    if ($percentage > $this->number) {
                                        $this->number = $percentage + 1;
                                        event(new EventTrigger(['message' => 'Episode ' . $this->episode_number . ' is in processing', 'progress' => $percentage, 'tmdb_id' => $this->tmdb_id]));
                                    }
                                }
                            });

                            $newNameMP4 = str_random(20) . '.mp4';
                            $convertLow = FFMpeg::fromDisk('public')
                                ->open($videoValue['path'])
                                ->addFilter(function ($filters) {
                                    if ($this->WatermarkPosition !== null) {
                                        $filters->watermark(storage_path('/app/public/watermark/' . Transcoder::first()->watermark_url), $this->WatermarkPosition);
                                    }
                                })
                                ->export()
                                ->toDisk('s3_private')
                                ->inFormat($lowBitrate)
                                ->save( $path_upload . $newNameMP4);

                            // Store video data
                            $video = new Video();
                            $video->episode_id = $videoValue['id'];
                            $video->resolution = '320';
                            $video->video_url =  $path_upload . $newNameMP4;
                            $video->video_cloud = 'aws';
                            $video->video_format = 'mp4';
                            $video->save();
                        }
                    }
                }
            }



            Storage::deleteDirectory('public/temp');
            Storage::deleteDirectory('public/' . $path_upload);

            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcoding video to AWS S3'], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'There is something error with video'], 422);
        }
    }


    public function analysisSeries($id)
    {
        if (preg_match('/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}/', $id) !== 1) {
            return response()->json(['status' => 'faild', 'message' => 'Error in id'], 404);
        }

        $check = Series::find($id);
        if (is_null($check)) {
            return response()->json(['status' => 'faild', 'message' => 'There is no series found'], 404);
        }

        $viewsInDay = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.episode_id) AS number, episodes.name AS name, HOUR(recently_watcheds.created_at) AS hour')
            ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
            ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE() AND series.t_id= "' . $id . '"')
            ->groupBy('recently_watcheds.episode_id')
            ->limit(10)
            ->get();

        // Monthly
        $viewsInMonth = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.episode_id) AS number, episodes.name AS name, MONTH(recently_watcheds.created_at) AS month')
            ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
            ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE() AND series.t_id= "' . $id . '"')
            ->groupBy('recently_watcheds.episode_id')
            ->limit(10)
            ->get();

        // Yearly

        $viewsInYear = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.episode_id) AS number, episodes.name AS name, YEAR(recently_watcheds.created_at) AS year')
            ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
            ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND series.t_id= "' . $id . '"')
            ->groupBy('recently_watcheds.episode_id')
            ->limit(10)
            ->get();

        // Count Like
        $countLikeInDay = DB::table('likes')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE() AND series_id = "' . $id . '"')
            ->count();

        $countLikeInMonth = DB::table('likes')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 Month) AND CURRENT_DATE() AND series_id = "' . $id . '"')
            ->count();

        $countLikeInYear = DB::table('likes')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 Year) AND CURRENT_DATE() AND series_id = "' . $id . '"')
            ->count();


        // Count Favor
        $countFavorInDay = DB::table('collection_lists')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE() AND series_id = "' . $id . '"')
            ->count();

        $countFavorInMonth = DB::table('collection_lists')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 Month) AND CURRENT_DATE() AND series_id = "' . $id . '"')
            ->count();

        $countFavorInYear = DB::table('collection_lists')
            ->whereRaw('created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 Year) AND CURRENT_DATE() AND series_id = "' . $id . '"')
            ->count();


        $latestViews = DB::table('recently_watcheds')
            ->selectRaw('users.name AS user_name, users.id AS user_id, episodes.name AS name, episodes.season_number, episodes.episode_number, recently_watcheds.created_at')
            ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
            ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->join('users', 'users.id', '=', 'recently_watcheds.uid')
            ->whereRaw('series.t_id = "' . $id . '"')
            ->orderBy('recently_watcheds.created_at', 'DESC')
            ->limit(20)
            ->get();


        $topViews = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.episode_id) AS number, episodes.name AS name, episodes.season_number, episodes.episode_number, recently_watcheds.created_at')
            ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
            ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->whereRaw('series.t_id = "' . $id . '"')
            ->orderBy('recently_watcheds.episode_id', 'DESC')
            ->limit(20)
            ->get();


        return response()->json([
            'status' => 'success',
            'data' => [
                'views' => [
                    'day' => $viewsInDay,
                    'month' => $viewsInMonth,
                    'year' => $viewsInYear
                ],
                'like' => [
                    'day' => $countLikeInDay,
                    'month' => $countLikeInMonth,
                    'year' => $countLikeInYear
                ],
                'favor' => [
                    'day' => $countFavorInDay,
                    'month' => $countFavorInMonth,
                    'year' => $countFavorInYear
                ],
                'latest_views' => $latestViews,
                'top_episode' => $topViews,
                'all_views' => DB::table('recently_watcheds')->where('series_id', $id)->count()
            ]
        ]);
    }


}
