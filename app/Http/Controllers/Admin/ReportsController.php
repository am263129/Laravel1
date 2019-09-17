<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Report;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{

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
     * Get all reports
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllReports()
    {
        $getReports = DB::table('reports')
            ->selectRaw('reports.id,reports.report_movie,users.name,movies.m_name,series.t_name,COUNT(reports.report_movie) AS movies_count, COUNT(reports.report_episode) AS series_count, COUNT(reports.report_userid) AS users_count,series.t_id,series.t_poster,movies.m_poster,movies.m_id, 
              count(report_readit)AS report_not_readit
            ')
            ->leftJoin('users', 'users.id', '=', 'reports.report_userid')
            ->leftJoin('episodes', 'episodes.id', '=', 'reports.report_episode')
            ->leftJoin('series', 'series.t_id', '=', 'reports.report_series')
            ->leftJoin('movies', 'movies.m_id', '=', 'reports.report_movie')
            ->groupBy('reports.report_movie', 'reports.report_series')
            ->where('report_readit', '=', 0)
            ->paginate(10);

        if (empty($getReports->all())) {
            $getReports = null;
        }

        return response()->json([
            'data' => [
                'reports' => $getReports
            ],
        ]);
    }

    /**
     * Get all reports of movie or series
     *
     * @param [integer] $id
     * @return \Illuminate\Http\Response
     */
    public function getReport($id)
    {
        $check = Report::find($id);
        if (!is_null($check)) {
            if ($check->report_movie !== null) {
                $getReport = DB::table('reports')
                    ->selectRaw('"movie" AS type ,reports.id,report_readit,reports.created_at,reports.report_details,reports.report_type,users.name AS username,movies.m_name AS name,COUNT(reports.report_movie) AS count')
                    ->leftJoin('users', 'users.id', '=', 'reports.report_userid')
                    ->leftJoin('movies', 'movies.m_id', '=', 'reports.report_movie')
                    ->where('reports.report_movie', $check->report_movie)
                    ->groupBy('reports.id')
                    ->paginate(10);
            } elseif ($check->report_episode !== null) {
                $getReport = DB::table('reports')
                    ->selectRaw('"series" AS type, reports.id,report_readit,reports.created_at,reports.report_details,reports.report_type,users.name AS username,series.t_name AS name,COUNT(reports.report_series) AS count, episodes.episode_number, episodes.season_number')
                    ->Join('users', 'users.id', '=', 'reports.report_userid')
                    ->Join('series', 'series.t_id', '=', 'reports.report_series')
                    ->Join('episodes', 'episodes.id', '=', 'reports.report_episode')
                    ->where('reports.report_series', $check->report_series)
                    ->groupBy('reports.id')
                    ->paginate(10);
            }
        } else {
            abort(404);
        }

        if (empty($getReport->all())) {
            $getReport = null;
        }

        return response()->json([
            'data' => [
                'reports' => $getReport,
            ],
        ]);
    }

    /**
     * Delete one report from movie or series
     *
     * @param [type] $id
     * @return void
     */
    public function deleteOneReport($id)
    {
        $check = Report::where('reports.id', $id)->first();
        if (is_null($check)) {
            abort(401);
        }
        Report::where('reports.id', $id)->delete();
        return response()->json(['status' => 'deleted', 'message' => 'Successful delete']);
    }

    /**
     * Delete allreport of movie or series
     *
     * @param [type] $id
     * @return void
     */
    public function deleteAllReports($id)
    {
        $checkMovie = Report::where('reports.report_movie', $id)->first();
        $checkSeries = Report::where('reports.report_series', $id)->first();
        if (! is_null($checkMovie)) {
            Report::where('reports.report_movie', $id)->delete();
        } elseif (! is_null($checkSeries)) {
            Report::where('reports.report_series', $id)->delete();
        } else {
            abort(401);
        }
        return response()->json(['status' => 'deleted', 'message' => 'Successful delete all report']);
    }


    /**
     * When the admin read report this function will set the report as read it
     *
     * @param [type] $id
     * @return void
     */
    public function readit($id)
    {
        $check = Report::where('reports.id', $id)->first();
        if (is_null($check)) {
            abort(401);
        }
        $update = Report::find($id);
        $update->report_readit = 1;
        $update->save();
        return response()->json(['status' => 'success']);
    }
}
