<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use Uuids;
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    protected $primaryKey = 't_id';


    public function referrals()
    {
        return $this->morphMany('App\Referral', 'referrable');
    }


}
