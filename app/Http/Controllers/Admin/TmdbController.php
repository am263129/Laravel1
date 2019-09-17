<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tmdb;
use Auth;
use DB;

class TmdbController extends Controller
{



  
    /**
     * Only admin can access
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin']);
            return $next($request);
        });
    }

    public function get() {
        $get = DB::table('tmdbs')->first();

        return response()->json(['status' => 'success', 'data' => $get]);

    }

    public function update(Request $request) {

        $get = Tmdb::find(1);

        $get->api = $request->input('api');
        $get->language = $request->input('language');
        $get->save();
        
        return response()->json(['status' => 'success', 'message' => 'Successful update']);
    }

}
