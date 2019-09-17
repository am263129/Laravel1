<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Movie;
use App\Series;
use App\Episode;
use App\Video;
use App\Tv;
use App\Casts;

class FileManagerController extends Controller
{
    public function getInsideFolder(Request $request)
    {
        if ($request->link === '/') {
            $directories = Storage::disk('public')->directories('/');
            $files = Storage::disk('public')->files('/');
        } else {
            $directories = Storage::disk('public')->directories($request->link);
            $files = Storage::disk('public')->files($request->link);
        }

        // Sort Files
        $fileFilter = [];
        foreach ($files as $key => $value) {
            $mime = substr($value, strpos($value, ".") + 1);
            $name = substr(strrchr($value, '/'), 1);

            $size =  Storage::disk('public')->size($value);
            $lastModified =  gmdate("Y-m-d", Storage::disk('public')->lastModified($value));
            if ($mime === 'jpeg' ||  $mime === 'jpg' || $mime === 'png') {
                array_push($fileFilter, ['mime' => $mime, 'path' => $value, 'name' => $name, 'size' => $size, 'date' =>  $lastModified ]);
            } elseif ($mime === 'mp4' || $mime === 'm3u8') {
                array_push($fileFilter, ['mime' => $mime, 'path' => $value, 'name' => $name, 'size' => $size, 'date' =>  $lastModified ]);
            }
        }

        // Sort Folder
        $folderFilter = [];
        foreach ($directories as $key => $value) {
            $name =  substr($value, strpos($value, "/"));
            array_push($folderFilter, ['path' => $value, 'name' => $name ]);
        }

        if (count($fileFilter) <= 0) {
            $fileFilter = null;
        }

        if (count($folderFilter) <= 0) {
            $folderFilter = null;
        }
        return response()->json(['status' => 'success', 'data' => ['folder' => $folderFilter, 'files' => $fileFilter]]);
    }

    public function deleteFileAndFolder(Request $request)
    {
        if ($request->type === 'file') {
            $delete = Storage::disk('public')->delete($request->path);
        } elseif ($request->type === 'folder') {
            $delete = Storage::disk('public')->deleteDirectory($request->path);
        }
        return response()->json(['status' => 'success', 'message' => 'Successful Delete']);
    }

    /**
     * Get Files Info
     *
     * @param Request $request
     * @return void
     */
    public function getFileInfo(Request $request)
    {
        $name =  substr($request->name, strpos($request->name, "_") + 1);

        $getInfoByMovieImage = Movie::where('m_poster', $name)->orWhere('m_backdrop', $name)->first();
        $getInfoBySeriesImage = Series::where('t_poster', $name)->orWhere('t_backdrop', $name)->first();
        $getInfoByEpisodeImage = Episode::join('series', 'series.t_id', '=', 'episodes.series_id')->where('backdrop', $name)->first();
        $getInfoByTvImage = Tv::where('image', $name)->first();
        $getInfoByVideo = Video::leftjoin('series', 'series.t_id', '=', 'videos.episode_id')->leftjoin('movies', 'movies.m_id', '=', 'videos.movie_id')->where('video_url', 'LIKE' , '%' . $request->name. '%')->first();
        $getInfoByCast = Casts::where('c_image', '/storage/casts/' . $request->name)->first();

        if (!is_null($getInfoByMovieImage)) {
            return response()->json(['status' => 'success', 'data' => $getInfoByMovieImage, 'type' => 'movie']);
        } elseif (!is_null($getInfoBySeriesImage)) {
            return response()->json(['status' => 'success', 'data' => $getInfoBySeriesImage, 'type' => 'series']);
        } elseif (!is_null($getInfoByEpisodeImage)) {
            return response()->json(['status' => 'success', 'data' => $getInfoByEpisodeImage, 'type' => 'episode']);
        } elseif (!is_null($getInfoByTvImage)) {
            return response()->json(['status' => 'success', 'data' => $getInfoByTvImage, 'type' => 'tv']);
        } elseif (!is_null($getInfoByVideo)) {
            return response()->json(['status' => 'success', 'data' => $getInfoByVideo, 'type' => 'video']);
        } elseif (!is_null($getInfoByCast)) {
            return response()->json(['status' => 'success', 'data' => $getInfoByCast, 'type' => 'cast']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'There is no info'], 422);
        }
    }

    /**
     * Create New Folder
     *
     * @param Request $request
     * @return void
     */
    public function createNewFolder(Request $request)
    {
        $createFolder = Storage::disk('public')->makeDirectory($request->path);
        if ($createFolder) {
            return response()->json(['status' => 'success', 'message' => 'Successful Create Folder In '. $request->path]);
        }
    }

    /**
     * Delete Folder
     *
     * @param Request $request
     * @return void
     */
    public function deleteFolder(Request $request)
    {
        $deleteDirectory = Storage::disk('public')->deleteDirectory($request->path);
        if ($deleteDirectory) {
            return response()->json(['status' => 'success', 'message' => 'Successful Delete Folder In '. $request->path]);
        }
    }

    /**
     * Rename Folder
     *
     * @param Request $request
     * @return void
     */
    public function renameFolderAndFile(Request $request)
    {
        $deleteDirectory = Storage::disk('public')->move($request->old_name, $request->new_name);
        if ($deleteDirectory) {
            return response()->json(['status' => 'success', 'message' => 'Successful Rename Folder In '. $request->path]);
        }
    }

    /**
    * Rename Folder
    *
    * @param Request $request
    * @return void
    */
    public function uploadFiles(Request $request)
    {   
        if ($request->has('files') && $request->has('path')) {
            foreach ($request->file('files') as $key => $value) {
                $uploadFiles = Storage::disk('public')->put($request->path, $value);
            }

            return response()->json(['status' => 'success', 'message' => 'Successful Upload'], 200);
        }
    }
}
