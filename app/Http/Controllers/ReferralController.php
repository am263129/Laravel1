<?php

namespace App\Http\Controllers;

use App\Referral;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Tv;
use App\Movie;
use App\Video;
use App\Series;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return auth()->user()->referredPeople;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'referral_id' => 'required',
            'email' => 'required'
        ]);
        $user = \App\User::whereEmail($request->email)->first();

        $referral = \App\User::where(DB::raw('MD5(email)'), $request->referral_id)->first();
        $referral->points = $referral->points + \App\Siteinfo::first()->registration_points;
        $referral->save();
        return $referral->referredPeople()->save($user);
    }

    public function storeMediaReferral(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'type' => 'required'
        ]);

        $referral = new Referral();
        $referral->type = $request->type;

        $referral->user()->associate($request->referral);

        $referral->save();

        switch ($request->type) {
            case 'tv':
                $target = Tv::find($request->id);
                $target->referrals()->save($referral);
                break;
            case 'movie':
                $target = Movie::find($request->id);
                $target->referrals()->save($referral);
                break;
            case 'video':
                $target = Video::find($request->id);
                $target->referrals()->save($referral);
                break;
            case 'series':
                $target = Series::find($request->id);
                $target->referrals()->save($referral);
                break;
            case 'series':
                $target = Series::find($request->id);
                $target->referrals()->save($referral);
                break;
        }

        return response()->json($referral);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Referral  $referral
     * @return \Illuminate\Http\Response
     */
    public function show(Referral $referral)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Referral  $referral
     * @return \Illuminate\Http\Response
     */
    public function edit(Referral $referral)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Referral  $referral
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Referral $referral)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Referral  $referral
     * @return \Illuminate\Http\Response
     */
    public function destroy(Referral $referral)
    {
        //
    }
}
