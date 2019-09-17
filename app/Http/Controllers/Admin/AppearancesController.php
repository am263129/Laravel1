<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Siteinfo;
use Auth;

class AppearancesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin']);
            return $next($request);
        });
    }


    public function get()
    {
        return response()->json([
            'status' => 'success',
            'data' => Siteinfo::first()
        ]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'facebook' => 'nullable|url|max:150',
            'twitter' => 'nullable|url|max:150',
            'instagram' => 'nullable|url|max:150',
            'privacy' => 'nullable|string',
            'terms' => 'nullable|string',
            'about' => 'nullable|string',
        ]);

        $check = Siteinfo::find(1);
        if (is_null($check)) {
            // New
            $create = new Siteinfo();
            if ($request->has('facebook')) {
                $create->social_facebook = $request->input('facebook');
            }
            if ($request->has('twitter')) {
                $create->social_twitter = $request->input('twitter');
            }
            if ($request->has('instagram')) {
                $create->social_instagram = $request->input('instagram');
            }
            if ($request->has('privecy')) {
                $create->privacy = $request->input('privacy');
            }
            if ($request->has('terms')) {
                $create->terms = $request->input('terms');
            }
            if ($request->has('about')) {
                $create->about = $request->input('about');
            }
            $create->save();
            return response()->json(['status' => 'success', 'message' => 'Successsful Update']);
        } else {
            if ($request->has('facebook')) {
                $check->social_facebook = $request->input('facebook');
            }
            if ($request->has('twitter')) {
                $check->social_twitter = $request->input('twitter');
            }
            if ($request->has('instagram')) {
                $check->social_instagram = $request->input('instagram');
            }
            if ($request->has('privacy')) {
                $check->privacy = $request->input('privacy');
            }
            if ($request->has('terms')) {
                $check->terms = $request->input('terms');
            }
            if ($request->has('about')) {
                $check->about = $request->input('about');
            }
            $check->save();
            return response()->json(['status' => 'success', 'message' => 'Successsful Update']);
        }
    }
}
