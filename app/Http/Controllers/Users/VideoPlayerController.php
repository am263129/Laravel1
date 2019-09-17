<?php

namespace App\Http\Controllers\Users;

use App\Episode;
use App\Helpers;
use App\Http\Controllers\Controller;
use App\Movie;
use App\Recently_watched;
use App\Report;
use App\Series;
use App\Subtitle;
use App\Video;
use App\Live_tv;
use Carbon\Carbon;
use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Aws\CloudFront\CloudFrontClient;

class VideoPlayerController extends Controller
{


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function getMovieVideo(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|uuid',
        ]);

        // Check this movie is exist
        $checkMovie = DB::table('movies')
            ->select('m_id AS id', 'm_cloud AS cloud', 'm_name AS name', 'm_desc AS overview', 'm_poster AS poster', 'm_runtime AS runtime', 'm_year AS year', 'm_genre AS genre', 'show', 'recently_watcheds.current_time', 'recently_watcheds.duration_time', 'm_category AS category')
            ->leftJoin('recently_watcheds', function ($join) {
                $join->on('recently_watcheds.movie_id', '=', 'movies.m_id')
                    ->where('recently_watcheds.uid', '=', Auth::id());
            })
            ->where('m_id', $request->input('movie_id'))
            ->first();

        if (is_null($checkMovie)) {
            return response()->json(['status' => 404], 404);
        }

        // Create AWS Singed Cookies

        $cookie_key = null;
        $cookie_policy = null;
        $cookie_signature = null;


        $cloudFront = new CloudFrontClient([
            'region' => config('aws.region'),
            'version' => config('aws.version')
        ]);

        // Set up parameter values for the resource
        $resourceKey = config('aws.cloudfront_private_url') . '/movies/*';
        $expires = time() + (86400 * 30);

        $customPolicy = <<<POLICY
                    {
                        "Statement": [
                            {
                                "Resource": "{$resourceKey}",
                                "Condition": {
                                    "DateLessThan": {"AWS:EpochTime": {$expires}}
                                }
                            }
                        ]
                        }
POLICY;
        
       


        // // Create a signed URL for the resource using a custom policy
        // $signedCookieCustomPolicy = $cloudFront->getSignedCookie([
        //     'policy' => $customPolicy,
        //     'private_key' => storage_path(config('aws.cloudfront_private_key_file')),
        //     'key_pair_id' => 'APKAIDEXWNMP3WDGWN4A',
        //     //'key_pair_id' => config('aws.cloudfront_public_key')
        // ]);
        
        
        // $expires = 60*12; //minutes

        // //$cfLink = '.' . substr(config('aws.cloudfront_private_url'), strpos(config('aws.cloudfront_private_url'), ".") + 1);
        // $cfLink = 'd1gjsmoo5vvr7p.cloudfront.net';
        
        // $cloudfront_query_url = "";

        // foreach ($signedCookieCustomPolicy as $name => $value) {
        //     //if ($name === 'CloudFront-Key-Pair-Id') {
        //         $cloudfront_query_url .= "&"."$name=".$value;
        //         //$cookie_key = cookie($name, $value, $expires, "", $cfLink, true, true);
        //     //} elseif ($name === 'CloudFront-Policy') {
        //     //    $cloudfront_query_url .= "&".$name."=".$value;
        //         // $cookie_policy = cookie($name, $value, $expires, "", $cfLink, true, true);
        //     //} else {
        //     //    $cookie_signature = "cookie($name, $value, $expires, "", $cfLink, true, true);
        //     //}
        // }
        // $cloudfront_query_url = "?".ltrim($cloudfront_query_url,"&");

        

        $getVideo = Video::select('video_format', 'video_cloud', 'resolution', 'video_url')->where('movie_id', $checkMovie->id)->get();
        $getSubtitle = Subtitle::where('movie_id', $checkMovie->id)->get();


        $videoArray = [];
        $subArray = [];

        if ($getVideo->isEmpty()) {
            $getVideo = null;
        } else {
            foreach ($getVideo as $key => $videoValue) {
                if ($videoValue->video_format == 'hls') {
                    if ($videoValue->video_cloud === 'local') {
                        $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                    } elseif ($videoValue->video_cloud === 'link') {
                        $videoArray[$key]['file'] = $videoValue->video_url;
                    } else {

                        $signedUrl = $cloudFront->getSignedUrl([
                            'url' => config('aws.cloudfront_private_url') . $videoValue->video_url,
                            'policy' => $customPolicy,
                            'key_pair_id' => config('aws.cloudfront_keypair_id'),
                            'private_key' => storage_path(config('aws.cloudfront_private_key_file'))            
                        ]);

                        $videoArray[$key]['file'] = $signedUrl;
                    }
                    $videoArray[$key]['type'] = 'application/vnd.apple.mpegurl';
                } elseif ($videoValue->video_format == 'mp4') {
                    if ($videoValue->video_cloud === 'local') {
                        $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                    } elseif ($videoValue->video_cloud === 'link') {
                        $videoArray[$key]['file'] = $videoValue->video_url;
                    } else {
                        $signedUrl = $cloudFront->getSignedUrl([
                            'url' => config('aws.cloudfront_private_url') . $videoValue->video_url,
                            'policy' => $customPolicy,
                            'key_pair_id' => config('aws.cloudfront_keypair_id'),
                            'private_key' => storage_path(config('aws.cloudfront_private_key_file'))            
                        ]);
                        $videoArray[$key]['file'] = $signedUrl;
                        
                    }
                    if ($videoValue->resolution == '4k') {
                        $videoArray[$key]['label'] = '4K';
                    } else {
                        $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                    }
                    $videoArray[$key]['type'] = 'video/mp4';
                } else {
                    if ($videoValue->resolution == '4k') {
                        $videoArray[$key]['label'] = '4K';
                    } else {
                        $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                    }
                    $videoArray[$key]['file'] = $videoValue->video_url;
                }
            }
        }


        if ($getSubtitle->isEmpty()) {
            $getSubtitle = null;
        } else {
            foreach ($getSubtitle as $key => $subValue) {
                $subArray[] = [
                    'file' => url('/') . $subValue->name,
                    'label' => ucwords($subValue->language),
                    "kind" => "captions"
                ];
            }
        }

        if ($checkMovie->cloud === 'local') {
            $checkMovie->poster = url('/') . '/storage/posters/600_' . $checkMovie->poster;
        } else {
            $checkMovie->poster = config('aws.cloudfront_public_url') . '/posters/' . $checkMovie->poster;
        }

        $playlist[0] = [
            'VideoNumber' => 1,
            'title' => $checkMovie->name,
            'description' => $checkMovie->overview,
            'image' => $checkMovie->poster,
            'sources' => $videoArray,
            'tracks' => $subArray,
            'withCredentials' => true

        ];


        // Get Similar and Create Playlist
        $getSimilarMovies = DB::table('movies')
            ->selectRaw('m_id AS id, m_name AS name,m_poster AS poster, m_cloud AS cloud, m_desc AS overview, m_genre AS genre, created_at')
            ->where('m_category', '=', $checkMovie->category)
            ->where('m_id', '<>', $checkMovie->id)
            ->limit(7)
            ->get();


        if ($getSimilarMovies->isEmpty()) {
            $getSimilarMovies = null;
        } else {
            foreach ($getSimilarMovies as $key => $value) {
                $getVideo = Video::select('video_format', 'video_cloud', 'resolution', 'video_url')->where('movie_id', $value->id)->get();
                $getSubtitle = Subtitle::where('movie_id', $value->id)->get();

                $videoArray = [];
                $subArray = [];

                if ($getVideo->isEmpty()) {
                    $getVideo = null;
                } else {
                    foreach ($getVideo as $key => $videoValue) {
                        if ($videoValue->video_format == 'hls') {
                            if ($videoValue->video_cloud === 'local') {
                                $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                            } elseif ($videoValue->video_cloud === 'link') {
                                $videoArray[$key]['file'] = $videoValue->video_url;
                            } else {
                                $signedUrl = $cloudFront->getSignedUrl([
                                    'url' => config('aws.cloudfront_private_url') . $videoValue->video_url,
                                    'policy' => $customPolicy,
                                    'key_pair_id' => config('aws.cloudfront_keypair_id'),
                                    'private_key' => storage_path(config('aws.cloudfront_private_key_file'))            
                                ]);        
                                $videoArray[$key]['file'] = $signedUrl;                                
                            }

                            $videoArray[$key]['type'] = 'application/vnd.apple.mpegurl';
                        } elseif ($videoValue->video_format == 'mp4') {
                            if ($videoValue->video_cloud === 'local') {
                                $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                            } elseif ($videoValue->video_cloud === 'link') {
                                $videoArray[$key]['file'] = $videoValue->video_url;
                            } else {
                                $signedUrl = $cloudFront->getSignedUrl([
                                    'url' => config('aws.cloudfront_private_url') . $videoValue->video_url,
                                    'policy' => $customPolicy,
                                    'key_pair_id' => config('aws.cloudfront_keypair_id'),
                                    'private_key' => storage_path(config('aws.cloudfront_private_key_file'))            
                                ]);        
                                $videoArray[$key]['file'] = $signedUrl;                                     
                            }
                            if ($videoValue->resolution == '4k') {
                                $videoArray[$key]['label'] = '2160p';
                            } else {
                                $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                            }
                            $videoArray[$key]['type'] = 'video/mp4';
                        } else {
                            if ($videoValue->resolution == '4k') {
                                $videoArray[$key]['label'] = '2160p';
                            } else {
                                $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                            }
                            $videoArray[$key]['file'] = $videoValue->video_url;
                        }                        
                    }
                }

                if ($getSubtitle->isEmpty()) {
                    $getSubtitle = null;
                } else {
                    foreach ($getSubtitle as $key => $subValue) {
                        $subArray[] = [
                            'file' => url('/') . $subValue->name,
                            'label' => ucwords($subValue->language),
                            "kind" => "captions"
                        ];
                    }
                }

                if ($value->cloud === 'local') {
                    $value->poster = url('/') . '/storage/posters/600_' . $value->poster;
                } else {
                    $value->poster = config('aws.cloudfront_public_url') . '/posters/' . $value->poster;
                }

                $playlist[] = [
                    'title' => $value->name,
                    'VideoNumber' => $key + 1,
                    'description' => $value->overview,
                    'image' => $value->poster,
                    'sources' => $videoArray,
                    'tracks' => $subArray,
                    'withCredentials' => true

                ];
            }
        }


        return response()->json(
            ['status' => 'success',
                'data' => [
                    'playlist' => [
                        'playlist' => $playlist
                    ],
                    'suggestion' => $getSimilarMovies,
                    'current_movie' => $checkMovie,
                    'geo_status' => true

                ]]
        );
        // ->cookie($cookie_key)
        //     ->cookie($cookie_policy)
        //     ->cookie($cookie_signature);
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function getVideoSongVideo(Request $request)
    {
        $request->validate([
            'videosong_id' => 'required|uuid',
        ]);

        // Check this movie is exist
        $checkMovie = DB::table('video_songs')
            ->select('v_id AS id', 'v_cloud AS cloud', 'v_name AS name', 'v_desc AS overview', 'v_poster AS poster', 'v_runtime AS runtime', 'v_year AS year', 'v_genre AS genre', 'show','v_category AS category')            
            ->where('v_id', $request->input('videosong_id'))
            ->first();

        if (is_null($checkMovie)) {
            return response()->json(['status' => 404], 404);
        }

        // Create AWS Singed Cookies

        $cookie_key = null;
        $cookie_policy = null;
        $cookie_signature = null;


        $cloudFront = new CloudFrontClient([
            'region' => config('aws.region'),
            'version' => config('aws.version')
        ]);

        // Set up parameter values for the resource
        $resourceKey = config('aws.cloudfront_private_url') . '/VideoSongs/*';
        $expires = time() + (86400 * 30);

        $customPolicy = <<<POLICY
                    {
                        "Statement": [
                            {
                                "Resource": "{$resourceKey}",
                                "Condition": {
                                    "DateLessThan": {"AWS:EpochTime": {$expires}}
                                }
                            }
                        ]
                        }
POLICY;
        

        // $expires = 12*60;
        // // Create a signed URL for the resource using a custom policy
        // $signedCookieCustomPolicy = $cloudFront->getSignedCookie([
        //     'policy' => $customPolicy,
        //     'private_key' => storage_path(config('aws.cloudfront_private_key_file')),
        //     'key_pair_id' => config('aws.cloudfront_public_key')
        // ]);


        // $cfLink = '.' . substr(config('aws.cloudfront_private_url'), strpos(config('aws.cloudfront_private_url'), ".") + 1);

        // foreach ($signedCookieCustomPolicy as $name => $value) {
        //     if ($name === 'CloudFront-Key-Pair-Id') {
        //         $cookie_key = cookie($name, $value, $expires, "", $cfLink, false, true);
        //     } elseif ($name === 'CloudFront-Policy') {
        //         $cookie_policy = cookie($name, $value, $expires, "", $cfLink, false, true);
        //     } else {
        //         $cookie_signature = cookie($name, $value, $expires, "", $cfLink, false, true);
        //     }
        // }

        $getVideo = Video::select('video_format', 'video_cloud', 'resolution', 'video_url')->where('videosong_id', $checkMovie->id)->get();
        $getSubtitle = Subtitle::where('videosong_id', $checkMovie->id)->get();


        $videoArray = [];
        $subArray = [];

        if ($getVideo->isEmpty()) {
            $getVideo = null;
        } else {
            foreach ($getVideo as $key => $videoValue) {
                if ($videoValue->video_format == 'hls') {
                    if ($videoValue->video_cloud === 'local') {
                        $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                    } elseif ($videoValue->video_cloud === 'link') {
                        $videoArray[$key]['file'] = $videoValue->video_url;
                    } else {
                        $signedUrl = $cloudFront->getSignedUrl([
                            'url' => config('aws.cloudfront_private_url') . $videoValue->video_url,
                            'policy' => $customPolicy,
                            'key_pair_id' => config('aws.cloudfront_keypair_id'),
                            'private_key' => storage_path(config('aws.cloudfront_private_key_file'))            
                        ]);        
                        $videoArray[$key]['file'] = $signedUrl;                             
                    }

                    $videoArray[$key]['type'] = 'application/vnd.apple.mpegurl';
                } elseif ($videoValue->video_format == 'mp4') {
                    if ($videoValue->video_cloud === 'local') {
                        $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                    } elseif ($videoValue->video_cloud === 'link') {
                        $videoArray[$key]['file'] = $videoValue->video_url;
                    } else {
                        $signedUrl = $cloudFront->getSignedUrl([
                            'url' => config('aws.cloudfront_private_url') . $videoValue->video_url,
                            'policy' => $customPolicy,
                            'key_pair_id' => config('aws.cloudfront_keypair_id'),
                            'private_key' => storage_path(config('aws.cloudfront_private_key_file'))            
                        ]);        
                        $videoArray[$key]['file'] = $signedUrl;                             
                    }
                    if ($videoValue->resolution == '4k') {
                        $videoArray[$key]['label'] = '4K';
                    } else {
                        $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                    }
                    $videoArray[$key]['type'] = 'video/mp4';
                } else {
                    if ($videoValue->resolution == '4k') {
                        $videoArray[$key]['label'] = '4K';
                    } else {
                        $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                    }
                    $videoArray[$key]['file'] = $videoValue->video_url;
                }
            }
        }


        if ($getSubtitle->isEmpty()) {
            $getSubtitle = null;
        } else {
            foreach ($getSubtitle as $key => $subValue) {
                $subArray[] = [
                    'file' => url('/') . $subValue->name,
                    'label' => ucwords($subValue->language),
                    "kind" => "captions"
                ];
            }
        }

        if ($checkMovie->cloud === 'local') {
            $checkMovie->poster = url('/') . '/storage/posters/600_' . $checkMovie->poster;
        } else {
            $checkMovie->poster = config('aws.cloudfront_public_url') . '/posters/' . $checkMovie->poster;
        }

        $playlist[0] = [
            'VideoNumber' => 1,
            'title' => $checkMovie->name,
            'description' => $checkMovie->overview,
            'image' => $checkMovie->poster,
            'sources' => $videoArray,
            'tracks' => $subArray,
            'withCredentials' => true

        ];


        // Get Similar and Create Playlist
        $getSimilarMovies = DB::table('video_songs')
            ->selectRaw('v_id AS id, v_name AS name,v_poster AS poster, v_cloud AS cloud, v_desc AS overview, v_genre AS genre, created_at')
            ->where('v_category', '=', $checkMovie->category)
            ->where('v_id', '<>', $checkMovie->id)
            ->limit(7)
            ->get();


        if ($getSimilarMovies->isEmpty()) {
            $getSimilarMovies = null;
        } else {
            foreach ($getSimilarMovies as $key => $value) {
                $getVideo = Video::select('video_format', 'video_cloud', 'resolution', 'video_url')->where('videosong_id', $value->id)->get();
                $getSubtitle = Subtitle::where('videosong_id', $value->id)->get();

                $videoArray = [];
                $subArray = [];

                if ($getVideo->isEmpty()) {
                    $getVideo = null;
                } else {
                    foreach ($getVideo as $key => $videoValue) {
                        if ($videoValue->video_format == 'hls') {
                            if ($videoValue->video_cloud === 'local') {
                                $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                            } elseif ($videoValue->video_cloud === 'link') {
                                $videoArray[$key]['file'] = $videoValue->video_url;
                            } else {
                                $signedUrl = $cloudFront->getSignedUrl([
                                    'url' => config('aws.cloudfront_private_url') . $videoValue->video_url,
                                    'policy' => $customPolicy,
                                    'key_pair_id' => config('aws.cloudfront_keypair_id'),
                                    'private_key' => storage_path(config('aws.cloudfront_private_key_file'))            
                                ]);        
                                $videoArray[$key]['file'] = $signedUrl;                                     
                            }

                            $videoArray[$key]['type'] = 'application/vnd.apple.mpegurl';
                        } elseif ($videoValue->video_format == 'mp4') {
                            if ($videoValue->video_cloud === 'local') {
                                $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                            } elseif ($videoValue->video_cloud === 'link') {
                                $videoArray[$key]['file'] = $videoValue->video_url;
                            } else {
                                $signedUrl = $cloudFront->getSignedUrl([
                                    'url' => config('aws.cloudfront_private_url') . $videoValue->video_url,
                                    'policy' => $customPolicy,
                                    'key_pair_id' => config('aws.cloudfront_keypair_id'),
                                    'private_key' => storage_path(config('aws.cloudfront_private_key_file'))            
                                ]);        
                                $videoArray[$key]['file'] = $signedUrl;                                     
                            }
                            if ($videoValue->resolution == '4k') {
                                $videoArray[$key]['label'] = '2160p';
                            } else {
                                $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                            }
                            $videoArray[$key]['type'] = 'video/mp4';
                        } else {
                            if ($videoValue->resolution == '4k') {
                                $videoArray[$key]['label'] = '2160p';
                            } else {
                                $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                            }
                            $videoArray[$key]['file'] = $videoValue->video_url;
                        }
                    }
                }

                if ($getSubtitle->isEmpty()) {
                    $getSubtitle = null;
                } else {
                    foreach ($getSubtitle as $key => $subValue) {
                        $subArray[] = [
                            'file' => url('/') . $subValue->name,
                            'label' => ucwords($subValue->language),
                            "kind" => "captions"
                        ];
                    }
                }

                if ($value->cloud === 'local') {
                    $value->poster = url('/') . '/storage/posters/600_' . $value->poster;
                } else {
                    $value->poster = config('aws.cloudfront_public_url') . '/posters/' . $value->poster;
                }

                $playlist[] = [
                    'title' => $value->name,
                    'VideoNumber' => $key + 1,
                    'description' => $value->overview,
                    'image' => $value->poster,
                    'sources' => $videoArray,
                    'tracks' => $subArray,
                    'withCredentials' => true

                ];
            }
        }


        return response()->json(
            ['status' => 'success',
                'data' => [
                    'playlist' => [
                        'playlist' => $playlist
                    ],
                    'suggestion' => $getSimilarMovies,
                    'current_movie' => $checkMovie,
                    'geo_status' => true

                ]]
                );
            // ->cookie($cookie_key)
            // ->cookie($cookie_policy)
            // ->cookie($cookie_signature);
    }



    /**
     * Get Episode Video
     *
     * @param Request $request
     * @return void
     */
    public function getEpisodeVideo(Request $request)
    {
        $request->validate([
            'episode_id' => 'nullable|uuid',
            'series_id' => 'nullable|uuid',
        ]);


        // Create AWS Singed Cookies

        $cookie_key = null;
        $cookie_policy = null;
        $cookie_signature = null;


        $cloudFront = new CloudFrontClient([
            'region' => config('aws.private_bucket'),
            'version' => '2014-11-06'
        ]);
        // Set up parameter values for the resource
        $resourceKey = config('aws.cloudfront_private_url') . '/series/*';
        $expires = time() + (86400 * 30);

        $customPolicy = <<<POLICY
                    {
                        "Statement": [
                            {
                                "Resource": "{$resourceKey}",
                                "Condition": {
                                    "DateLessThan": {"AWS:EpochTime": {$expires}}
                                }
                            }
                        ]
                        }
POLICY;

        // Create a signed URL for the resource using a custom policy
        $signedCookieCustomPolicy = $cloudFront->getSignedCookie([
            'policy' => $customPolicy,
            'private_key' => storage_path(config('aws.cloudfront_private_key_file')),
            'key_pair_id' => config('aws.cloudfront_public_key')
        ]);


        $cfLink = '.' . substr(config('aws.cloudfront_private_url'), strpos(config('aws.cloudfront_private_url'), ".") + 1);
        
        
        foreach ($signedCookieCustomPolicy as $name => $value) {
            if ($name === 'CloudFront-Key-Pair-Id') {
                $cookie_key = cookie($name, $value, $expires, "", $cfLink, true, true);
            } elseif ($name === 'CloudFront-Policy') {
                $cookie_policy = cookie($name, $value, $expires, "", $cfLink, true, true);
            } else {
                $cookie_signature = cookie($name, $value, $expires, "", $cfLink, true, true);
            }
        }


        if ($request->has('series_id') && !$request->has('episode_id')) {
            // Check if the series is exist already
            $checkAlreadySeries = Series::where('t_id', $request->input('series_id'))->first();
            $getFirstSeason = Episode::where('series_id', $request->input('series_id'))->first();

            if (is_null($checkAlreadySeries)) {
                return response()->json(['status' => 404], 404);
            }

            if( $getFirstSeason){
                $season_number = $getFirstSeason->season_number;
            }else{
                $season_number ="";
            }
            // Get Season One
            $getSeasonOne = DB::table('episodes')
                ->selectRaw('episodes.*, recently_watcheds.current_time, recently_watcheds.duration_time')
                ->leftJoin('recently_watcheds', function ($join) {
                    $join->on('recently_watcheds.episode_id', '=', 'episodes.id')
                        ->where('recently_watcheds.uid', '=', Auth::id());
                })
                ->where('episodes.series_id', $request->input('series_id'))
                ->where('episodes.season_number', $season_number)
                ->where('episodes.show', '<>', 0)
                ->orderBy('episode_number', 'ASC')
                ->get();




            // Get All Seasons
            $getAllSeason = DB::table('episodes')
                ->select('season_number', 'series_id')
                ->where('series_id', $request->input('series_id'))
                ->where('show', '<>', 0)
                ->groupBy('season_number')
                ->orderBy('season_number', 'ASC')
                ->get();


            // SORT
            $playlist = [];

            // Check if there is no cast
            if ($getSeasonOne->isEmpty()) {
                $season = null;
            } else {

                // Sort season and episode
                foreach ($getSeasonOne as $key => $value) {
                    $getVideo = Video::select('video_format', 'video_cloud', 'resolution', 'video_url')->where('episode_id', $value->id)->get();
                    $getSubtitle = Subtitle::where('episode_id', $value->id)->get();

                    $videoArray = [];
                    $subArray = [];

                    if ($getVideo->isEmpty()) {
                        $getVideo = null;
                    } else {
                        foreach ($getVideo as $key => $videoValue) {
                            if ($videoValue->video_format == 'hls') {
                                if ($videoValue->video_cloud === 'local') {
                                    $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                                } elseif ($videoValue->video_cloud === 'link') {
                                    $videoArray[$key]['file'] = $videoValue->video_url;
                                } else {
                                    $videoArray[$key]['file'] = config('aws.cloudfront_private_url') .'/'. $videoValue->video_url;
                                }

                                $videoArray[$key]['type'] = 'application/vnd.apple.mpegurl';
                            } elseif ($videoValue->video_format == 'mp4') {
                                if ($videoValue->video_cloud === 'local') {
                                    $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                                } elseif ($videoValue->video_cloud === 'link') {
                                    $videoArray[$key]['file'] = $videoValue->video_url;
                                } else {
                                    $videoArray[$key]['file'] = config('aws.cloudfront_private_url') .'/'. $videoValue->video_url;
                                }

                                if ($videoValue->resolution == '4k') {
                                    $videoArray[$key]['label'] = '4K';
                                } else {
                                    $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                                }
                                $videoArray[$key]['type'] = 'video/mp4';
                            } else {
                                if ($videoValue->resolution == '4k') {
                                    $videoArray[$key]['label'] = '4K';
                                } else {
                                    $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                                }
                                $videoArray[$key]['file'] = $videoValue->video_url;
                            }
                        }
                    }


                    if ($getSubtitle->isEmpty()) {
                        $getSubtitle = null;
                    } else {
                        foreach ($getSubtitle as $key => $subValue) {
                            $subArray[] = [
                                'file' => url('/') . $subValue->name,
                                'label' => ucwords($subValue->language),
                                "kind" => "captions"
                            ];
                        }
                    }

                    if ($value->cloud === 'local') {
                        $value->backdrop = url('/') . '/storage/backdrops/300_' . $value->backdrop;
                    } else {
                        $value->backdrop = config('aws.cloudfront_public_url') . '/backdrops/' . $value->backdrop;
                    }

                    $playlist[] = [
                        'title' => $value->name,
                        'VideoNumber' => $value->episode_number,
                        'description' => $value->overview,
                        'image' => $value->backdrop,
                        'sources' => $videoArray,
                        'tracks' => $subArray,
                        'withCredentials' => true
                    ];
                }
            }


            $seasonArray = [];
            if ($getSeasonOne->isEmpty()) {
                $seasonArray = null;
            } else {
                foreach ($getSeasonOne as $key => $value) {
                    array_push($seasonArray, $value);
                }
            }


            return response()->json(
                ['status' => 'success',
                    'data' => [
                        'playlist' => [
                            'playlist' => $playlist
                        ],
                        'series' => $checkAlreadySeries,
                        'seasons' => $getAllSeason,
                        'current_season' => $seasonArray,
                        'current_episode' => $seasonArray[0],
                        'geo_status' => true

                    ]]
            )->cookie($cookie_key)
                ->cookie($cookie_policy)
                ->cookie($cookie_signature);
        }


        // Get Episode When Click To Episode
        if ($request->has('series_id') && $request->has('episode_id')) {

            // Check if the series is exist already
            $checkAlreadyEpisode = Episode::select('series.t_name AS name', 'episodes.*')
                ->join('series', 'series.t_id', 'episodes.series_id')
                ->where('episodes.id', $request->input('episode_id'))
                ->first();

            if (is_null($checkAlreadyEpisode)) {
                return response()->json(['status' => 404], 404);
            }

            // Get Season One
            $getSeasonOne = DB::table('episodes')
                ->selectRaw('episodes.*, recently_watcheds.current_time, recently_watcheds.duration_time')
                ->leftJoin('recently_watcheds', function ($join) {
                    $join->on('recently_watcheds.episode_id', '=', 'episodes.id')
                        ->where('recently_watcheds.uid', '=', Auth::id());
                })
                ->where('episodes.series_id', $request->input('series_id'))
                ->where('episodes.season_number', $checkAlreadyEpisode->season_number)
                ->where('episodes.show', '<>', 0)
                ->orderBy('episode_number', 'ASC')
                ->get();

            $getAllSeason = DB::table('episodes')
                ->selectRaw('season_number, series_id')
                ->where('series_id', $request->input('series_id'))
                ->where('show', '<>', 0)
                ->groupBy('season_number')
                ->orderBy('season_number', 'ASC')
                ->get();

            // SORT

            $season = [];
            $playlist = [];
            $current_episode = null;

            // Check if there is no cast
            if ($getSeasonOne->isEmpty()) {
                $season = null;
            } else {

                // Sort season and episode
                foreach ($getSeasonOne as $key => $value) {
                    $getVideo = Video::select('video_format', 'video_cloud', 'resolution', 'video_url')->where('episode_id', $value->id)->get();
                    $getSubtitle = Subtitle::where('episode_id', $value->id)->get();
                    $videoArray = [];
                    $subArray = [];


                    if ($getVideo->isEmpty()) {
                        $getVideo = null;
                    } else {
                        foreach ($getVideo as $key => $videoValue) {
                            if ($videoValue->video_format == 'hls') {
                                if ($videoValue->video_cloud === 'local') {
                                    $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                                } elseif ($videoValue->video_cloud === 'link') {
                                    $videoArray[$key]['file'] = $videoValue->video_url;
                                } else {
                                    $videoArray[$key]['file'] = config('aws.cloudfront_private_url') . '/' . $videoValue->video_url;
                                }

                                $videoArray[$key]['type'] = 'application/vnd.apple.mpegurl';
                            } elseif ($videoValue->video_format == 'mp4') {

                                if ($videoValue->video_cloud === 'local') {
                                    $videoArray[$key]['file'] = url('/') . $videoValue->video_url;
                                } elseif ($videoValue->video_cloud === 'link') {
                                    $videoArray[$key]['file'] = $videoValue->video_url;
                                } else {
                                    $videoArray[$key]['file'] = config('aws.cloudfront_private_url') . '/' . $videoValue->video_url;

                                }

                                if ($videoValue->resolution == '4k') {
                                    $videoArray[$key]['label'] = '4K';
                                } else {
                                    $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                                }
                                $videoArray[$key]['type'] = 'video/mp4';
                            } else {
                                if ($videoValue->resolution == '4k') {
                                    $videoArray[$key]['label'] = '4K';
                                } else {
                                    $videoArray[$key]['label'] = $videoValue->resolution . 'p';
                                }
                                $videoArray[$key]['file'] = $videoValue->video_url;
                            }
                        }
                    }


                    if ($getSubtitle->isEmpty()) {
                        $getSubtitle = null;
                    } else {
                        foreach ($getSubtitle as $key => $subValue) {
                            $subArray[] = [
                                'file' => url('/') . $subValue->name,
                                'label' => ucwords($subValue->language),
                                "kind" => "captions"
                            ];
                        }
                    }

                    if ($value->cloud === 'local') {
                        $value->backdrop = url('/') . '/storage/backdrops/300_' . $value->backdrop;
                    } else {
                        $value->backdrop = config('aws.cloudfront_public_url') . '/backdrops/' . $value->backdrop;
                    }

                    if ($value->id == $request->input('episode_id')) {
                        $current_episode = $value;
                        $playlist[0] = [
                            'title' => $value->name,
                            'VideoNumber' => $value->episode_number,
                            'description' => $value->overview,
                            'image' => $value->backdrop,
                            'sources' => $videoArray,
                            'tracks' => $subArray,
                            'withCredentials' => true

                        ];
                    } else {
                        $playlist[] = [
                            'title' => $value->name,
                            'VideoNumber' => $value->episode_number,
                            'description' => $value->overview,
                            'image' => $value->backdrop,
                            'sources' => $videoArray,
                            'tracks' => $subArray,
                            'withCredentials' => true

                        ];
                    }
                }
            }

            $seasonArray = [];
            if ($getSeasonOne->isEmpty()) {
                $seasonArray = null;
            } else {
                foreach ($getSeasonOne as $key => $value) {
                    array_push($seasonArray, $value);
                }
            }


            return response()->json(
                ['status' => 'success',
                    'data' => [
                        'playlist' => [
                            'playlist' => $playlist
                        ],
                        'series' => $checkAlreadyEpisode,
                        'seasons' => $getAllSeason,
                        'current_season' => $seasonArray,
                        'current_episode' => $current_episode,
                        'geo_status' => true

                    ]]
            )->cookie($cookie_key)
                ->cookie($cookie_policy)
                ->cookie($cookie_signature);
        }


    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function movieReport(Request $request)
    {
        $request->validate([
            'type' => 'required|numeric',
            'details' => 'nullable|max:100',
            'id' => 'required|uuid',
        ]);

        $add = new Report();
        $add->report_type = $request->type;
        $add->report_details = $request->details;
        $add->report_userid = Auth::user()->id;
        $add->report_movie = $request->id;
        $add->save();

        return response()->json(['status' => 'success']);
    }

    public function seriesReport(Request $request)
    {
        $request->validate([
            'type' => 'required|numeric',
            'details' => 'nullable|max:100',
            'episode_id' => 'required|uuid',
            'series_id' => 'required|uuid',
        ]);

        $add = new Report();
        $add->report_type = $request->type;
        $add->report_details = $request->details;
        $add->report_userid = Auth::user()->id;
        $add->report_episode = $request->episode_id;
        $add->report_series = $request->series_id;
        $add->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Store recently time episode
     *
     * @return \Illuminate\Http\Response
     */
    public function setRecentlyTimeEpiosde(Request $request)
    {
        $request->validate([
            'current_time' => 'required|numeric',
            'duration_time' => 'required|numeric',
            'episode_id' => 'required|uuid',
            'series_id' => 'required|uuid',
        ]);

        $recently = Recently_watched::join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->where('recently_watcheds.uid', Auth::id())
            ->where('episodes.id', '=', $request->episode_id)
            ->where('episodes.series_id', '=', $request->series_id)
            ->orderBy('episodes.episode_number', 'DESC')
            ->first();

        if (!is_null($recently)) {
            $recently->episode_id = $request->episode_id;
            $recently->current_time = $request->current_time;
            $recently->duration_time = $request->duration_time;
            $recently->save();
            return response()->json(['status' => 'success']);
        } else {
            $store = new Recently_watched();
            $store->episode_id = $request->episode_id;
            $store->series_id = $request->series_id;
            $store->uid = Auth::id();
            $store->current_time = $request->current_time;
            $store->duration_time = $request->duration_time;
            $store->save();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed'], 404);
    }

    /**
     * Store recently time episode
     *
     * @return \Illuminate\Http\Response
     */
    public function setRecentlyTimeMovie(Request $request)
    {
        $request->validate([
            'current_time' => 'required|numeric',
            'duration_time' => 'required|numeric',
            'movie_id' => 'required|uuid',
        ]);

        $recently = Recently_watched::where('uid', Auth::id())->where('movie_id', $request->movie_id)->first();

        if (!is_null($recently)) {
            $recently->current_time = $request->current_time;
            $recently->save();
            return response()->json(['status' => 'success']);
        } else {
            $store = new Recently_watched();
            $store->movie_id = $request->movie_id;
            $store->uid = Auth::id();
            $store->current_time = $request->current_time;
            $store->duration_time = $request->duration_time;
            $store->save();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed'], 404);
    }


    /**
     * Get All Seasons Content
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSeason(Request $request)
    {
        $request->validate([
            'season_number' => 'required|numeric',
            'series_id' => 'required|uuid',
        ]);

        $getSeason = DB::table('episodes')
            ->selectRaw('episodes.*, recently_watcheds.current_time, recently_watcheds.duration_time')
            ->leftJoin('recently_watcheds', function ($join) {
                $join->on('recently_watcheds.episode_id', '=', 'episodes.id')
                    ->where('recently_watcheds.uid', '=', Auth::id());
            })
            ->where('episodes.series_id', $request->input('series_id'))
            ->where('episodes.season_number', $request->input('season_number'))
            ->where('episodes.show', '<>', 0)
            ->orderByRaw('episodes.season_number, episode_number + 0 ASC')
            ->get();



        $seasonArray = [];
        if ($getSeason->isEmpty()) {
            $seasonArray = null;
        } else {
            foreach ($getSeason as $key => $value) {
                if ($value->show == 3) {
                    if (Carbon::now() >= $value->publish_date && Carbon::now() <= $value->expire_date) {
                        if ($value->cloud === 'local') {
                            $value->backdrop = url('/') . '/storage/backdrops/300_' . $value->backdrop;
                        } else {
                            $value->backdrop =  config('aws.cloudfront_public_url')  . '/backdrops/' .  $value->backdrop;
                        }

                        array_push($seasonArray, $value);
                    }
                } else {
                    if ($value->cloud === 'local') {
                        $value->backdrop = url('/') . '/storage/backdrops/300_' . $value->backdrop;
                    } else {
                        $value->backdrop =  config('aws.cloudfront_public_url')  . '/backdrops/' .  $value->backdrop;
                    }

                    array_push($seasonArray, $value);
                }
            }
        }

        return response()->json(
            ['status' => 'success',
                'data' => $seasonArray
            ]
        );
    }
}
