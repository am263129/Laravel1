<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Siteinfo;

class UsersController extends Controller
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
     * Get all users
     *
     * @return void
     */
    public function getAllUsers()
    {
        $getAllUsers = DB::table('users')
            ->selectRaw('id,name,email,created_at,confirmed,period_end,braintree_id,card_brand,created_at,updated_at')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        // Check if empty result
        if (empty($getAllUsers->all())) {
            $getAllUsers = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $getAllUsers
            ],
        ]);
    }

    /**
     * Get inactivity users
     *
     * @return void
     */

    public function getInactivityUsers()
    {
        $getInactivityUsers = DB::table('users')
            ->selectRaw('id,name,email,created_at,confirmed,period_end,created_at,updated_at')
            ->whereRaw('period_end < '. date("Y/m/d"))
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        // Check if empty result
        if (empty($getInactivityUsers->all())) {
            $getInactivityUsers = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $getInactivityUsers
            ],
        ]);
    }

    /**
     * Get blocked users
     *
     * @return void
     */

    public function getActivityUsers()
    {

      // Users Analysis
        $payemnt_status = '';
        if( ! Siteinfo::find(1)->payment_status) {
        
        $getActitivyUsers = DB::table('users')
            ->selectRaw('id,name,email,created_at,confirmed,period_end,created_at,updated_at')
            ->whereRaw('period_end >'. date("Y/m/d"))
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        }else{
            $getActitivyUsers = DB::table('users')
            ->selectRaw('id,name,email,created_at,confirmed,period_end,created_at,updated_at')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);
        }

        // Check if empty result
        if (empty($getActitivyUsers->all())) {
            $getActitivyUsers = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $getActitivyUsers
            ],
        ]);
    }

    public function delete($id)
    {
        $getUser = User::find($id);

        if (is_null($getUser)) {
            return response()->json(['status' => 'failed', 'message' => 'Not found any user'], 422);
        }

        $getUser->delete();
        return response()->json(['status' => 'success', 'message' => 'Successful delete ' . $getUser->name]);
    }

    public function getUserDetails($id)
    {
        $getUser = User::find($id);

        if (is_null($getUser)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no id'], 422);
        }

        return response()->json(
            ['status' => 'success',
             'data' => [
                 'id' => $getUser->id,
                 'name' => $getUser->name,
                 'email' => $getUser->email,
                 'phone' => $getUser->phone,
                 'braintree_id' => $getUser->braintree_id,
                 'card_brand' => $getUser->card_brand,
                 'language' => $getUser->language,
                 'period_end' => $getUser->period_end,
                 'created_at' => $getUser->created_at,
                 'updated_at' => $getUser->updated_at,
             ]]
        );
    }


    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
            'email' => 'required|email|max:150',
            'phone' => 'required|max:12',
            'subscribe' => 'required|string|max:8',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $checkAlreadyEmail = User::where('email', $request->email)->where('id', '<>', $request->input('id'))->first();
        
        if (! is_null($checkAlreadyEmail)) {
            return response()->json(['status' => 'failed', 'message' => 'The email already exists'], 422);
        } else {
            $user->email = $request->input('email');
        }

        $user->phone = $request->input('phone');

        if ($request->input('password') !== null) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->confirmation_code = str_random(30);
        $user->period_end = date("Y-m-d H:i:s", strtotime("+1 ". $request->subscribe));
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'Successful create '. $user->name, 'data' => (date('d') > 28) ? date("Y-m-d H:i:s", strtotime("last day of next month")) : date("Y-m-d H:i:s", strtotime("+1 ". $request->subscribe))] );
    }



    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
            'name' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
            'email' => 'required|email|max:150',
            'phone' => 'required|max:12',
            'braintree_id' => 'nullable',
            'subscribe' => 'string|max:8|nullable',
        ]);

        $getUser = User::find($request->input('id'));

        if (is_null($getUser)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no id'], 422);
        }

        $getUser->name = $request->input('name');

        $checkAlreadyEmail = User::where('email', $request->email)->where('id', '<>', $request->input('id'))->first();
        if (! is_null($checkAlreadyEmail)) {
            return response()->json(['status' => 'failed', 'message' => 'The email already exists'], 422);
        } else {
            $getUser->email = $request->input('email');
        }

        $getUser->phone = $request->input('phone');

        if ($request->input('password') !== null) {
            $getUser->password = Hash::make($request->input('password'));
        }
        

        if ($request->input('subscribe') !== null) {
            $getUser->period_end = date("Y-m-d H:i:s", strtotime("+1 ". $request->subscribe));

        }


        $getUser->save();
        return response()->json(['status' => 'success', 'message' => 'Successful update '. $getUser->name]);
    }

    public function getUsersBySearch(Request $request)
    {
        $request->validate([
            'query' => 'nullable|max:50',
        ]);

        if (empty($request->input('query'))) {
            $getUsers = null;
        } else {
            $getUsers = DB::table('users')
                ->selectRaw('id,name,email,created_at,confirmed,period_end,created_at,updated_at')
                ->where('email', 'like', $request->input('query') . '%')
                ->orWhere('name', 'like', $request->input('query') . '%')
                ->limit(10)
                ->get();

            // Check if empty result
            if ($getUsers->isEmpty()) {
                $getUsers = null;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $getUsers
            ],
        ]);
    }

    public function getUserInvoice(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
        ]);

        $user = User::find($request->id);

        if ($user->hasBraintreeId()) {
            $invoices = $user->invoices()->map(function ($invoice) {
                return [
                    'start' => $invoice->subscription['billingPeriodStartDate']->format('Y-m-d'),
                    'end' => $invoice->subscription['billingPeriodEndDate']->format('Y-m-d'),
                    'total' => $invoice->amount,
                ];
            });
            $subscription = $user->subscription('main')->asBraintreeSubscription();
        } else {
            $invoices = [];
        }

        return response()->json([
            'status' => 'success',
            'data' => ['invoices' => $invoices,
                'name' => $subscription->planId,
                'amount' => $subscription->price,
            ]]);
    }
}
