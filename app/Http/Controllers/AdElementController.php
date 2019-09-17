<?php

namespace App\Http\Controllers;

use App\AdElement;
use Illuminate\Http\Request;

class AdElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(AdElement::all());
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
        $data = $request->all();
        $data['data_ad_client'] = env('AD_SENSE_CLIENT_ID', '');
        return response()->json(AdElement::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdElement  $adElement
     * @return \Illuminate\Http\Response
     */
    public function show($type)
    {   
        $ae = AdElement::wherePlace($type)->first();
        if(!$ae) {
            return response()->json(AdElement::first());
        }

        return response()->json($ae);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdElement  $adElement
     * @return \Illuminate\Http\Response
     */
    public function edit(AdElement $adElement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdElement  $adElement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $adElement)
    {
        AdElement::find($adElement)->update($request->all());
        return response()->json(AdElement::find($adElement));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdElement  $adElement
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdElement $adElement)
    {
        //
    }
}
