<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'points'
    ];

    public function paymentMethod()
    {
        return $this->belongsTo('App\PaymentMethod');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
