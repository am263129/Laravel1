<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    //

    /**
     * Get the owning imageable model.
     */
    public function referrable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
