<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Auth;
use App\Transcoder;
use Illuminate\Support\Facades\Storage;
class TranscoderController extends Controller
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


    public function get() {
        // Get from db   
        $getFromDb = Transcoder::first();
        return response()->json([ 'data' => $getFromDb ]);
    }


     /**
     * Upload watermark
     *
     * @param Request $request
     * @return void
     */
    public function uploadWatermark(Request $request) {

        $hash_name = str_random(20) . '.png';
        $image_encode = \Image::make($request->file('watermark'))->widen(100)->encode('png');
        Storage::disk('public')->put('watermark/' . $hash_name, $image_encode->__toString());

        $getFromDb = Transcoder::find(1);
        $getFromDb->watermark_url = $hash_name;
        $getFromDb->watermark_position = $request->watermark_position;
        $getFromDb->segment_duration = $request->segment_duration;
        $getFromDb->save();
        return response()->json(['status' => 'success', 'message' => 'Successful Update']);
    }


  /**
     * Remove watermark
     *
     * @param Request $request
     * @return void
     */
    public function removeWatermark() {


        $getFromDb = Transcoder::find(1);
        $getFromDb->watermark_url = null;
        $getFromDb->watermark_position = null;
        $getFromDb->save();
        return response()->json(['status' => 'success', 'message' => 'Successful Remove']);
    }

}
