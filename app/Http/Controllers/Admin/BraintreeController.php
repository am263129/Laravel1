<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Braintree;
use Auth;
use App\Siteinfo;


class BraintreeController extends Controller
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



    /**
     * Get all plans
     *
     * @return void
     */
    public function getAllPlans()
    {
        try {
            $getPlanFromApi = \Braintree\Plan::all();
            $getPlanFromDB = Braintree::select('plan_id')->get();
            $arrayPlan = [];
            $fPDB = [];

            foreach ($getPlanFromDB as $k => $v) {
                array_push($fPDB, $v->plan_id);
            }

            if(count($getPlanFromApi) > 0) {

                foreach ($getPlanFromApi as $apiKey => $value) {
                    if (in_array($value->id, $fPDB)) {
                        $arrayPlan[$apiKey] = ['active' => true, 'plan' => $value];
                    } else {
                        $arrayPlan[$apiKey] = ['active' => false, 'plan' => $value];
                    }

                }

            }else {
                $arrayPlan = null;
            }


            return response()->json([
                'status' => 'success',
                'data' =>  $arrayPlan,
                'payment_gateway_status' => Siteinfo::find(1)->payment_status
            ], 200);

        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' =>  "Braintree is not connected, Please check your Braintree account info is correct in .env file",
            ], 200);
        }


    }


    /**
     * Active plan
     *
     * @return void
     */
    public function activePlan(Request $request)
    {

        $check = Braintree::where('plan_id', $request->plan_id)->first();
        if( is_null($check) ) {

            $create = new Braintree();
            $create->plan_id       = $request->plan_id;
            $create->plan_name     = $request->plan_name;
            $create->plan_amount   = $request->plan_amount;
            $create->plan_currency = $request->plan_currency;
            $create->plan_trial    = $request->plan_trial;
            $create->save();

            return response()->json(['status' => 'success', 'message' => 'Successful add plan', 'type' => 'add'], 200);
        }else{

            $check->delete();

            return response()->json(['status' => 'success', 'message' => 'Successful delete plan', 'type' => 'delete'], 200);
        }
    }



    public function DisabledPayment() {
        $getInfo = Siteinfo::find(1);

        if($getInfo !== null) {
            if ($getInfo->payment_status) {
                $getInfo->payment_status = 0;
                $getInfo->save();
                return response()->json([
                    'status' => 'success',
                    'type' => 'inactive',
                    'message' => 'Successful inactive all payment gateway',
                ], 200);
            } else {
                $getInfo->payment_status = 1;
                $getInfo->save();
                return response()->json([
                    'status' => 'success',
                    'type' => 'active',
                    'message' => 'Successful active all payment gateway',
                ], 200);
            }
        }
    }
}
