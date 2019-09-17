<?php

namespace App\Http\Controllers;

use App\Withdrawal;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Siteinfo;
use App\PaymentMethod;

class WithdrawalController extends Controller
{

    public function balance()
    {
        return Response::json(['balance' => auth()->user()->points]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return response()->json(['data'=>Withdrawal::with('user')->with('paymentMethod')->get()],200);        
    }

    public function getForUser($userId){
        return User::find($userId)->withdrawals()->with('paymentMethod')->get();
    }

    public function user(){
        return $this->belongsTo('User');
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
            'points' => 'required',
            'payment_method' => 'required',
        ]);

        $withdrawal = new Withdrawal();

        $user = User::find(auth()->user()->id);
        if ($user->points < $request->points) {
            return response('You don\' have enough points', 409);
        }

        $paymentMethod = PaymentMethod::find($request->payment_method);

        $withdrawal->points_spent = $request->points;
        $amount = $request->points * Siteinfo::first()->registration_points;
        $withdrawal->amount = $amount;

        $withdrawal->paymentMethod()->associate($paymentMethod);

        $user->withdrawals()->save($withdrawal);

        return $withdrawal;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function show(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $withdrawal)
    {
        $request->validate([
            'status' => 'required'
        ]);
        $withdrawal = Withdrawal::find($withdrawal);
        $user = $withdrawal->user;
        $withdrawal->status = $request->status;

        if($request->status === 'accepted') {
            $user->points = $user->points - $withdrawal->points_spent;
            $user->save();
        }
        
        $withdrawal->save();
        return $withdrawal;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdrawal $withdrawal)
    {
        //
    }
}
