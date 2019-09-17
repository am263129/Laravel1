<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support;
use App\Support_responses;
use Auth;
use DB;

class SupportController extends Controller
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
     * Get all request of user
     *
     * @return void
     */
    public function getAllRequests() {
        $getRequests = DB::table('supports')
                        ->selectRaw('supports.*, users.name AS "from", support_responses.from AS last_reply,  MAX(support_responses.readit) AS readit ')
                        ->join('users', 'users.id', '=', 'supports.uid')
                        ->leftJoin('support_responses', function($join){
                            $join->on('support_responses.request_id', '=', 'supports.request_id')
                            ->where( 'support_responses.from', '<>', 'support');
                        })
                        ->groupBy('supports.id')
                        ->orderBy('support_responses.created_at', 'DESC')
                        ->paginate(20);

        return response()->json(['status' => 'success', 'data' => $getRequests], 200);

    }


    /**
     * Get open request of user
     *
     * @return void
     */
    public function getOpenRequests() {
        $getRequests = DB::table('supports')
                        ->selectRaw('supports.*, users.name AS "from",  MAX(support_responses.readit) AS readit ')
                        ->join('users', 'users.id', '=', 'supports.uid')
                        ->leftJoin('support_responses', function($join){
                            $join->on('support_responses.request_id', '=', 'supports.request_id')
                            ->where( 'support_responses.from', '<>', 'support');
                        })
                        ->where('supports.status', 1)
                        ->groupBy('supports.id')
                        ->orderBy('support_responses.created_at', 'DESC')
                        ->paginate(20);

        return response()->json(['status' => 'success', 'data' => $getRequests], 200);

    }

    /**
     * Get closed request of user
     *
     * @return void
     */
    public function getClosedRequests() {
        $getRequests = DB::table('supports')
                        ->selectRaw('supports.*, users.name AS "from",  MAX(support_responses.readit) AS readit ')
                        ->join('users', 'users.id', '=', 'supports.uid')
                        ->leftJoin('support_responses', function($join){
                            $join->on('support_responses.request_id', '=', 'supports.request_id')
                            ->where( 'support_responses.from', '<>', 'support');
                        })
                        ->where('supports.status', 0)
                        ->groupBy('supports.id', 'support_responses.readit')
                        ->orderBy('support_responses.created_at', 'DESC')
                        ->paginate(20);

        return response()->json(['status' => 'success', 'data' => $getRequests], 200);

    }


    /**
     * Create new support request
     *
     * @return void
     */
    public function getRequest($id) {


        // Check if this request for the user 
        $check = Support::where('id', $id)->first();

        if(is_null($check)) {
            return response()->json(['status' => 'success', 'There is no support request'], 204);            
        }

        $getAllMessage = DB::table('support_responses')
                        ->select('support_responses.reply', 'support_responses.from', 'support_responses.created_at')
                        ->join('supports', 'supports.request_id', '=', 'support_responses.request_id')
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
        $check = Support::where('id', $request->input('id'))->first();

        if(is_null($check)) {
            return response()->json(['status' => 'success', 'There is no support request'], 204);            
        }

        if($check->status === 0) {
            return response()->json(['status' => 'success', 'This request is closed'], 204);            
        }
        
        $create = new Support_responses();
        $create->from  = 'support';
        $create->reply = $request->reply;
        $create->request_id = $check->request_id;
        $create->save();

        return response()->json(['status' => 'success', 'data' => $create
        ], 200);

    }


    /**
     * Create new support request
     *
     * @return void
     */
    public function updateStatus($id) {
        // Check if this request for the user 
        $check = Support::where('id', $id)->first();

        if(is_null($check)) {
            return response()->json(['status' => 'success', 'There is no support request'], 204);            
        }

        if($check->status === 0) {
            $update = Support::find($check->id);
            $update->status = true;
            $update->save();
        }elseif($check->status === 1) {
            $update = Support::find($check->id);
            $update->status = false;
            $update->save();
        }else {
            return response()->json(['status' => 'success', 'This request is closed'], 204);            

        }


        return response()->json(['status' => 'success', 'data' => $update->status
        ], 200);

    }

    /**
     * Create new support request
     *
     * @return void
     */
    public function deleteRequest($id) {


        // Check if this request for the user 
        $check = Support::where('id', $id)->first();

        if(is_null($check)) {
            return response()->json(['status' => 'success', 'There is no support request'], 204);            
        }

        $create = Support::find($id);
        $create->delete();

        return response()->json(['status' => 'success' ], 200);

    }

    /**
     * Search 
     *
     * @return void
     */
    public function search(Request $request) {

        $request->validate([
            'query' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
        ]);
        
        $getRequests = DB::table('supports')
                        ->selectRaw('supports.*, users.name AS "from",  MAX(support_responses.readit) AS readit ')
                        ->join('users', 'users.id', '=', 'supports.uid')
                        ->leftJoin('support_responses', function($join){
                            $join->on('support_responses.request_id', '=', 'supports.request_id')
                            ->where( 'support_responses.from', '<>', 'support');
                        })
                        ->where('users.name', 'LIKE', $request->input('query') . '%')
                        ->orWhere('supports.request_id', 'LIKE',  $request->input('query') . '%')
                        ->paginate(20);

        return response()->json(['status' => 'success', 'data' => $getRequests], 200);

    }
}
