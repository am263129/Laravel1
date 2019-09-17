<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tv extends Model
{
    use Uuids;
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

          /**
     * Get the post's image.
     */
    public function referrals()
    {
        return $this->morphMany('App\Referral', 'referrable');
    }

}
