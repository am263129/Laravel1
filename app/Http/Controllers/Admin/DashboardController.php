<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Tmdb;
use App\Siteinfo;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return view('admin.home');
    }


    /**
     * Get users status
     *
     * @return \Illuminate\Http\Response
     */

    public function usersAnalysisByDay()
    {

        // Users Analysis
        $payemnt_status = '';
        if( ! Siteinfo::find(1)->payment_status) {
            $payemnt_status = "AND users.period_end > NOW()";
        }

        $activeUserDay = DB::table('users')
            ->selectRaw('"active" AS type, count(*) AS number, TIME(users.created_at) AS hour')
            ->whereRaw('users.created_at >= NOW() - INTERVAL 1 DAY AND CURRENT_DATE()'. $payemnt_status)
            ->groupBy('hour');

        $inactiveUserDay = DB::table('users')
            ->selectRaw('"inactive" AS type ,count(*) AS number, TIME(users.created_at) AS hour')
            ->whereRaw('users.created_at >= NOW() - INTERVAL 1 DAY AND CURRENT_DATE()'. $payemnt_status)
            ->groupBy('hour')
            ->union($activeUserDay)
            ->get();

        $activeUserMonth = DB::table('users')
            ->selectRaw('"active" AS type, count(*) AS number, MONTHNAME(users.created_at) AS month')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND CURRENT_DATE()' . $payemnt_status)
            ->groupBy('month');

        $inactiveUserMonth = DB::table('users')
            ->selectRaw('"inactive" AS type ,count(*) AS number, MONTHNAME(users.created_at) AS month')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND CURRENT_DATE()' . $payemnt_status)
            ->groupBy('month')
            ->union($activeUserMonth)
            ->get();


        $activeUserYear = DB::table('users')
            ->selectRaw('"active" AS type, count(*) AS number, YEAR(users.created_at) AS year')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND CURRENT_DATE()' . $payemnt_status)
            ->groupBy('year');

        $inactiveUserYear = DB::table('users')
            ->selectRaw('"inactive" AS type ,count(*) AS number, YEAR(users.created_at) AS year')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND CURRENT_DATE()' . $payemnt_status)
            ->groupBy('year')
            ->union($activeUserYear)
            ->get();


        // Top movie and series

        // Hourly
        $getTopEpisodeDay = DB::table('recently_watcheds')
                    ->selectRaw('"episode" AS type, count(recently_watcheds.episode_id) AS count, episodes.name AS name, series.t_name AS series_name, HOUR(recently_watcheds.created_at) AS hour')
                    ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
                    ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
                    ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE()')
                    ->groupBy('recently_watcheds.episode_id')
                    ->limit(10);

        $getTopmMovieDay = DB::table('recently_watcheds')
                    ->selectRaw('"movie" AS type, count(recently_watcheds.movie_id) AS count, movies.m_name AS name, null AS series_name, HOUR(recently_watcheds.created_at) AS hour')
                    ->join('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
                    ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE()')
                    ->groupBy('recently_watcheds.movie_id')
                    ->union($getTopEpisodeDay)
                    ->limit(10)
                    ->get();

        // Monthly
        $getTopEpisodeMonth = DB::table('recently_watcheds')
                    ->selectRaw('"episode" AS type, count(recently_watcheds.episode_id) AS count, episodes.name AS name, series.t_name AS series_name, MONTHNAME(recently_watcheds.created_at) AS month')
                    ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
                    ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
                    ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()')
                    ->groupBy('recently_watcheds.episode_id')
                    ->limit(10);

        $getTopmMovieMonth = DB::table('recently_watcheds')
                    ->selectRaw('"movie" AS type, count(recently_watcheds.movie_id) AS count, movies.m_name AS name, null AS series_name, MONTHNAME(recently_watcheds.created_at) AS month')
                    ->join('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
                    ->groupBy('recently_watcheds.movie_id')
                    ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()')
                    ->union($getTopEpisodeMonth)
                    ->limit(10)
                    ->get();

        // Yearly
        $getTopEpisodeYear = DB::table('recently_watcheds')
                    ->selectRaw('"episode" AS type, count(recently_watcheds.episode_id) AS count, episodes.name AS name, series.t_name AS series_name, YEAR(recently_watcheds.created_at) AS year')
                    ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
                    ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
                    ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE()')
                    ->groupBy('recently_watcheds.episode_id')
                    ->limit(10);


        $getTopmMovieYear = DB::table('recently_watcheds')
                    ->selectRaw('"movie" AS type, count(recently_watcheds.movie_id) AS count, movies.m_name AS name, null AS series_name, YEAR(recently_watcheds.created_at) AS year')
                    ->join('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
                    ->whereRaw('recently_watcheds.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE()')
                    ->groupBy('recently_watcheds.movie_id')
                    ->union($getTopEpisodeYear)
                    ->limit(10)
                    ->get();




        $top_movies = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.movie_id) AS movie_count,movies.m_name, movies.m_id')
            ->leftJoin('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
            ->groupBy('recently_watcheds.movie_id')
            ->orderByRaw('movie_count DESC')
            ->limit(10)
            ->get();

        $top_series = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.episode_id) AS series_count,episodes.name,series.t_name, series.t_id')
            ->leftJoin('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->leftJoin('series', 'series.t_id', '=', 'episodes.series_id')
            ->groupBy('recently_watcheds.episode_id')
            ->orderByRaw('series_count DESC')
            ->limit(10)
            ->get();

        $top_users = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.uid) AS user_count,users.name')
            ->join('users', 'users.id', '=', 'recently_watcheds.uid')
            ->groupBy('recently_watcheds.uid')
            ->orderByRaw('user_count DESC')
            ->limit(10)
            ->get();


        $regions = DB::Table('location_logs')
        ->selectRaw('country, count(country) As number')
        ->where('country', '<>', null)
        ->groupBy('country')
        ->get();


        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => [
                    'day' =>  $inactiveUserDay,
                    'month' => $inactiveUserMonth,
                    'year' =>  $inactiveUserYear
                ],
                'top' => [
                    'day' => $getTopmMovieDay,
                    'month' => $getTopmMovieMonth,
                    'year' =>  $getTopmMovieYear
                ],
                'count' => [
                    'reports' => DB::table('reports')->count(),
                    'movies' => DB::table('movies')->count(),
                    'series' => DB::table('series')->count(),
                    'tvs' => DB::table('tvs')->count(),
                    'users' => DB::table('users')->count()
                ],
                'top_all_time' => [
                    'movies' => $top_movies,
                    'series' => $top_series,
                    'users' => $top_users
                ],
                'regions' => $regions
            ]
        ]);
    }


    public function checkServices() {

        // Check FFmpeg

        $ffmpeg = trim(shell_exec('which ffmpeg')); // or better yet:
        if (empty($ffmpeg))
        {
            $ffmpeg_status  = false;
            $ffmpeg_message = "There is not FFmpeg in your server, please install it before upload movie/episode or just use External link and Embed Link";
        }else{
            $ffmpeg_status  = true;
            $ffmpeg_message = "FFmpeg is successful connected, FFmpeg PATH: " . $ffmpeg ;
        }


        // Check Braintree

        try {
            $getPlanFromApi = \Braintree\Plan::all();
            $braintree_status = true;
            $braintree_message = "Braintree is successful connected";
        }
        catch (\Exception $e) {
            $braintree_status = false;
            $braintree_message = "Braintree is not connected, Please check your Braintree account info is correct in .env file";
        }

        // Check TMDB

        $getApi = Tmdb::find(1);

        if ($getApi->api === null || empty($getApi->api)) {
            $tmdb_status = false;
            $tmdb_message= "There is not TMDB API, Please add it form Settings -> TMDB API";
        }else {
            $tmdb_status = true;
            $tmdb_message= "TMDB is successful connected";
        }


        return response()->json([
            'ffmpeg' => [
                'status' => $ffmpeg_status,
                'message' => $ffmpeg_message
            ],
            'braintree' => [
                'status' => $braintree_status,
                'message' => $braintree_message
            ],
            'tmdb' => [
                'status' => $tmdb_status,
                'message' => $tmdb_message
            ],
            'notification' => [
                'reports' => DB::table('reports')->where('report_readit', '=', 0)->count(),
                'supports' => DB::table('supports')->where('status', '=', 1)->count()
            ],
        ], 200);
    }
}
