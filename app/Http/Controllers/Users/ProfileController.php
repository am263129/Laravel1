<?php

namespace App\Http\Controllers\Users;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Location_log;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;

class ProfileController extends Controller
{

    /**
     * This Constructer check if the user is make payment or not if not it will return 404
     *
     * ProfileController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Helpers::checkUserPayment(auth()->guard('api')->user());
            return $next($request);
        });
    }

    /**
     * Upload new image
     *
     * @param Request $request
     * @return void
     */
    public function avatarUpload(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,jpg,png|max:1000',
        ]);
        // upload to s3 storage
        $image = \Image::make($request->image)->widen(200)->encode('jpg');
        $name = str_random() . '.jpg';
        $upload = Storage::disk('public')->put('users_image/' . $name, $image->__toString());
        if ($upload) {
            $user = auth()->guard('api')->user();
            $user->image = '/storage/users_image/' . $name;
            $user->save();
            return response()->json(['status' => 'success', 'image' => $user->image]);
        }
    }

    /**
     * Update name and email
     *
     * @param Request $request
     * @return void
     */
    public function updateDetails(Request $request)
    {
        $request->validate([
            'current_password' => 'required|min:8',
            'name' => 'required|max:100',
            'email' => 'required|email|max:150',
        ]);


        $user = auth()->guard('api')->user();

        if (Hash::check($request->input('current_password'), $user->password)) {
            $user->name = $request->name;

            // Check if mail is exist in database
            $checkMail = User::where('email', $request->email)->where('id', '<>', $user->id)->first();
            if(is_null($checkMail)) {
                $user->email = $request->email;
            }else{
                return response()->json(['status' => 'failed', 'message' => 'Email has already been taken'], 422);
            }
            $user->save();
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Your password was incorrect'], 422);
        }
        return response()->json(['status' => 'success', 'message' => 'Successful update']);
    }

    /**
     * Update password
     *
     * @param Request $request
     * @return void
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'current_password' => 'required|min:8',

        ]);
        $user = auth()->guard('api')->user();

        if (Hash::check($request->input('current_password'), $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Successful update password']);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Your password was incorrect'], 422);
        }
    }

    /**
     * Get payment info
     *
     * @return void
     */
    public function getPaymentInfo()
    {
        if (!$this->checkUsermManually()) {
            return response()->json(['status' => 'success', 'message' => 'You cannot control billing, please contact with support if you have any probelm.'], 403);
        }

        $user = auth()->guard('api')->user();
        $subscription = $user->subscription('main')->asBraintreeSubscription();

        return response()->json(['status' => 'success', 'subscription_plan' => $subscription->planId, 'subscription_status' => $subscription->status, 'cancel' => $subscription->neverExpires ,'card_number' => $user->card_last_four, 'card_brand' => $user->card_brand]);
    }

    /**
     * Update payment
     *
     * @param Request $request
     * @return void
     */
    public function paymentUpdate(Request $request)
    {
        if (!$this->checkUsermManually()) {
            return response()->json(['status' => 'success', 'message' => 'You cannot control billing, please contact with support if you have any probelm.'], 403);
        }
        $user = auth()->guard('api')->user();
        $user->updateCard($request->token);
        return response()->json(['status' => 'success', 'message' => 'Successful update']);
    }

    /**
     * Get invoices
     *
     * @return void
     */
    public function getBillingDetails()
    {
        if (!$this->checkUsermManually()) {
            return response()->json(['status' => 'success', 'message' => 'You cannot control billing, please contact with support if you have any probelm.'], 403);
        }


        $user = auth()->guard('api')->user();
        if ($user->hasBraintreeId()) {
            $invoices = $user->invoices()->map(function ($invoice) {
                return [
                    'start' => $invoice->subscription['billingPeriodStartDate']->format('Y-m-d'),
                    'end' => $invoice->subscription['billingPeriodEndDate']->format('Y-m-d'),
                    'total' => $invoice->amount,
                ];
            });
        } else {
            $invoices = [];
        }
        $subscription = $user->subscription('main')->asBraintreeSubscription();


        return response()->json(['invoices' => $invoices,  'amount' => $subscription->price]);
    }

    /**
     * Cancel memebership
     *
     * @return boolean
     */
    public function cancelMembership()
    {
        if (!$this->checkUsermManually()) {
            return response()->json(['status' => 'success', 'message' => 'You cannot control billing, please contact with support if you have any probelm.'], 403);
        }
        $user = auth()->guard('api')->user();
        $user->subscription('main')->cancel();
        $subscription = $user->subscription('main')->asBraintreeSubscription();
        return response()->json(['status' => 'success', 'subscription_status' => $subscription->status, 'cancel' => $subscription->neverExpires]);
    }

    /**
     * Resume memebership
     *
     * @return void
     */
    public function resumeMembership()
    {
        if (!$this->checkUsermManually()) {
            return response()->json(['status' => 'success', 'message' => 'You cannot control billing, please contact with support if you have any probelm.'], 403);
        }
        $user = auth()->guard('api')->user();
        $user->subscription('main')->resume();
        $subscription = $user->subscription('main')->asBraintreeSubscription();
        return response()->json(['status' => 'success', 'subscription_status' => $subscription->status, 'cancel' => $subscription->neverExpires]);
    }

    /**
     * Change plan
     *
     * @param Request $request
     * @return void
     */
    public function changePlan(Request $request)
    {
        if (!$this->checkUsermManually()) {
            return response()->json(['status' => 'success', 'message' => 'You cannot control billing, please contact with support if you have any probelm.'], 403);
        }

        $request->validate([
            'plan_id' => 'min:1|max:20',
        ]);

        // Just add esleif statment and the request plan_id === the id number
        // $request->plan_id = 'Braintree id';
        $user = auth()->guard('api')->user();
        $user->subscription('main')
            ->swap($request->plan_id);

        return response()->json(['status' => 'success', 'message' => 'Successful Update']);
    }

    /**
     * Change language
     *
     * @param Request $request
     * @return void
     */
    public function changeLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|min:1|max:4',
        ]);
        $user = auth()->guard('api')->user();
        $user->language = $request->language;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Successful Update']);
    }

    /**
     *  Change caption
     *
     * @param Request $request
     * @return void
     */
    public function changeCaption(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'caption' => [
                'required',
                "regex:^(auto|0)$|^[+-]?[0-9]+.?([0-9]+)?(px|em|ex|%|in|cm|mm|pt|pc)$",
            ],
        ]);
        $caption = json_encode($request->caption);
        $user = auth()->guard('api')->user();
        $user->caption = $caption;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Successful Update']);
    }

    /**
     * Get viewing history
     *
     * @return void
     */
    public function getViewingHistory()
    {
        $getViewingHistoryMovie = DB::table('recently_watcheds')
            ->selectRaw('"movie" AS type ,movies.m_name AS name, movies.m_id AS id, null AS episode_number, recently_watcheds.created_at')
            ->join('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
            ->where('recently_watcheds.uid', auth()->guard('api')->id())
            ->limit(30);

        $getViewingHistorySeries = DB::table('recently_watcheds')
            ->selectRaw('"series" AS type ,series.t_name AS name, series.t_id AS id,episodes.episode_number, recently_watcheds.created_at')
            ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
            ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->where('recently_watcheds.uid', auth()->guard('api')->id())
            ->union($getViewingHistoryMovie)
            ->limit(30)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $getViewingHistorySeries,
        ]);
    }

    public function getDeviceActivity()
    {
        $getAllDeviceActivity = Location_log::where('uid', auth()->guard('api')->id())->orderBy('status', 'DESC')->get();

        if ($getAllDeviceActivity->isEmpty()) {
            $getAllDeviceActivity = null;
        }
        return response()->json([
            'status' => 'success',
            'data' => $getAllDeviceActivity,
        ], 200);
    }

    public function deleteDeviceSession($id)
    {
        $filter = [
            'id' => $id,
        ];

        $validator = Validator::make($filter, [
            'doctor_id' => 'required|numeric',
        ]);

        $getSession = Location_log::where('uid', auth()->guard('api')->id())->where('id', $id)->first();

        if (is_null($getSession)) {
            abort(404);
        } else {
            $delete = $getSession->delete();

            if ($delete) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Successful delete session',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error to delete session',
                ], 422);
            }

        }

    }
    public function logOutAPI(){
        if (auth()->guard('api')->user()) {
            auth()->guard('api')->user()->token()->revoke();
            return response()->json(['status' => 'success', 'message' => 'Successful Logout'], 200);
         }
    }

    private function checkUsermManually()
    {
        $user = auth()->guard('api')->user();
        if ($user->period_end > date("Y-m-d") && $user->paypal_email === null && $user->card_brand === null ) {
            return false;
        }
        return true;
    }

}
