<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, Billable, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    
    public function findForPassport($phone) {
        return $this->where('phone', $phone)->orWhere('email', $phone)->first();
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function AauthAcessToken()
    {
        return $this->hasMany('\App\OauthAccessToken');
    }

    public function referredMedia()
    {
        return $this->hasMany('App\Referral', 'user_id');
    }

    public function referredPeople()
    {
        return $this->hasMany('App\User', 'referred_by');
    }

    public function referredBy()
    {
        return $this->belongsTo('App\User', 'referred_by', 'user_id');
    }

    public function paymentMethods()
    {
        return $this->hasMany('App\PaymentMethod');
    }

    public function withdrawals()
    {
        return $this->hasMany('App\Withdrawal');
    }
}
