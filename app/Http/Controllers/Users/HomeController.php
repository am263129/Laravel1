<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Siteinfo;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home')->withCookie(cookie('coupon', "asdasdas", 3600));;
    }

    /**
     * Get App Details
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppDetails()
    {

        $getInfo = Siteinfo::first();

        return response()->json([

            'status' => 'success',
            'data' => [
                'social_facebook' => $getInfo->social_facebook,
                'social_twitter' => $getInfo->social_twitter,
                'social_instagram' => $getInfo->social_instagram,
                'privacy' => $getInfo->privacy,
                'terms' => $getInfo->terms,
                'int_gateway' => $getInfo->payment_status,
            ],
        ]);
    }
}
