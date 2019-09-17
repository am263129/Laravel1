<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support;
use App\Support_responses;
use Auth;
use DB;

class SupportController extends Controller
{
   
    /**
     * Get all request of user
     *
     * @return void
     */
    public function get() {
        
        // Check if user have support request
        $check = Support::where('uid', Auth::id())->first();

        if(is_null($check)) {
            return abort(204);
        }
        
        $get_requests = Support::where('uid', Auth::id())->get();

        return response()->json(['status' => 'success', 'data' => $get_requests], 200);

    }


    /**
     * Create new support request
     *
     * @return void
     */
    public function create(Request $request) {

        $request->validate([
            'subject' => 'required|string|max:50',
            'details' => 'required|string|max:2000',
        ]);

        $create = new Support();
        $create->uid = Auth::id();
        $create->subject = $request->input('subject');
        $create->details = $request->input('details');
        $create->request_id = str_random(20);
        $create->status = true;

        $create->save();

        return response()->json(['status' => 'success', 'data' => $create, 'message' => 'Successful send request'], 200);

    }



    /**
     * Create new support request
     *
     * @return void
     */
    public function getRequest($id) {


        // Check if this request for the user 
        $check = Support::where('id', $id)->where('uid', Auth::id())->first();

        if(is_null($check)) {
            return response()->json(['status' => 'success', 'There is no support request'], 204);            
        }

        $getAllMessage = DB::table('support_responses')
                        ->select('support_responses.reply', 'support_responses.from', 'support_responses.created_at')
                        ->join('supports', 'supports.request_id', '=', 'support_responses.request_id')
                        ->where('supports.uid', Auth::id())
                        ->where('supports.request_id', $check->request_id)
                        ->get();
        $updateReadit = DB::table('support_responses')->where('request_id', $check->request_id)->update(['readit' => 0]);

        return response()->json(['status' => 'success', 'data' => [
            'request' => $check,
            'reply' => $getAllMessage
        ]], 200);

    }

    /**
     * Create new support request
     *
     * @return void
     */
    public function submitReply(Request $request) {


        // Check if this request for the user 
        $check = Support::where('id', $request->input('id'))->where('uid', Auth::id())->first();

        if(is_null($check)) {
            return response()->json(['status' => 'success', 'There is no support request'], 204);            
        }

        if($check->status === 0) {
            return response()->json(['status' => 'success', 'This request is closed'], 204);            
        }

        $create = new Support_responses();
        $create->from  = 'client';
        $create->reply = $request->reply;
        $create->request_id = $check->request_id;
        $create->save();

        return response()->json(['status' => 'success', 'data' => $create
        ], 200);

    }

}
