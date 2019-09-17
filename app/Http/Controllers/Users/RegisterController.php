<?php

namespace App\Http\Controllers\Users;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Location_log;
use App\Mail\ActivityAccount;
use App\Mail\ForgetAccount;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    // Users Status
    // 1000 = payment_step
    // 1001 = payment_cancel
    // 1002 = payment_reactive
    // 1003 = confirm_mail
    // 1004 = active

    /**
     * New Register
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
            'email' => 'required|email|max:70',
            'password' => 'required|confirmed|min:8',
            'phone' => 'required|min:10|max:10',
        ]);

        $checkAlreadyEmail = User::where('email', $request->input('email'))->first();
        $checkAlreadyPhone = User::where('phone', $request->input('phone'))->first();

        // Check if the email is already
        if (!is_null($checkAlreadyEmail)) {
            return response()->json(['status' => 'error', 'message' => 'Email has already been taken.'], 400);
        }

        // Check if the email is already
        if (!is_null($checkAlreadyPhone)) {
            return response()->json(['status' => 'error', 'message' => 'Phone number has already been taken.'], 400);
        }

        // Get location
        $ipLocation = Helpers::getIp();
        $ipLocationJson = json_encode($ipLocation, true);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = Hash::make($request->input('password'));
        $user->confirmation_code = str_random(30);
        $user->location = $ipLocationJson;
        $user->save();

        // Send confirmation message to mail
        Auth::login($user);
        Mail::to(Auth::user())
        ->send(new ActivityAccount());

        // response success
        return response()->json(['status' => 'success', 'username' => $user->name, 'image' => 'img/avatar.png'], 200);
    }

    /**
     * Send mail confirm
     *
     * @return \Illuminate\Http\Response
     */
    public function sendActivityMail()
    {
        $user = Auth::user();
        if ($user->confirmed !== 1) {
            $user->confirmation_code = str_random(30);
            $user->save();
            Mail::to(Auth::user())
                ->send(new ActivityAccount());
            return response(['status' => 'success', 'message' => 'successful send to ' . $user->email]);
        } else {
            return response()->json(['status' => 404], 404);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $token
     * @return \Illuminate\Http\Response
     */
    public function codeConfirmed($token)
    {
        $user = User::where('confirmation_code', $token)->first();

        if (is_null($user)) {
            return redirect()->route('home');
        }

        $user->confirmation_code = null;
        $user->confirmed = 1;
        $user->save();
        return redirect('/app');
    }

    /**
     * Send mail to restore password
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (is_null($user)) {
            return response(['status' => 'error', 'message' => 'The email is incorrect'], 400);
        }

        $user->forget_code = str_random(30);
        $user->save();
        Mail::to($user)
            ->send(new ForgetAccount($user));
        return response(['status' => 'success', 'message' => 'A message has been sent to you by email with instructions on how to reset your password']);
    }

    /**
     * Check the code was sending to email to channe forget password
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function forgetCheckHash(Request $request)
    {
        $request->validate([
            'code' => 'required|max:30|regex:/^[a-z0-9 .\-]+$/i',
        ]);

        // Check if email and code is already
        $hash = User::where('forget_code', $request->input('code'))->first();

        if (!is_null($hash)) {
            return response()->json(['status' => 'success'], 200);
        }
        $user->forget_code = null;
        $user->save();

        abort(404);
    }

    /**
     * Change password after check if the code is correct
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function recoverPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|max:30|regex:/^[a-z0-9 .\-]+$/i',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('forget_code', $request->input('code'))->first();

        if (!is_null($user)) {
            $user->forget_code = null;
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Successful reset your password'], 200);
        } else {

            // if the code is incorrect
            $user->forget_code = null;
            $user->save();
            abort(404);
        }
    }

    /**
     * Make payment after success register
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request)
    {


        $request->validate([
            'plan' => 'required|min:1|max:40',
        ]);

        $user = Auth::user();

        $user->newSubscription('main', $request->plan)
                ->create($request->token);

        $details = $user->subscription('main')->asBraintreeSubscription();

        if($details->status == 'Active') {

            $user->period_end = $details->billingPeriodEndDate->format('Y-m-d');
            $user->save();
            return response()->json(['status' => 'success', 'name' => $user->name, 'email' => $user->email,  'card_number' => $user->card_last_four, 'card_brand' => $user->card_brand]);
        }


        return response()->json(['status' => 'failed', 'message' => 'Failed to accept payment'], 401);


    }

    /**
     * Confirm Device
     *
     * @param [type] $token
     * @return void
     */
    public function confirmDevice($token)
    {
        $getDeviceLocation = Location_log::where('confirm_hash', $token)->first();

        if (is_null($getDeviceLocation)) {
            return 'Verify Expired';
        } else {
            $getDeviceLocation->confirm_hash = null;
            $getDeviceLocation->status = 'off';
            $getDeviceLocation->save();
            return redirect('/app');
        }
    }
}
