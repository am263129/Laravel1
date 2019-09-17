<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    protected $primaryKey = 'v_id';
    protected $fillable = [
        'video_url',
        'video_cloud',
        'video_format'
    ];


    public function referrals()
    {
        return $this->morphMany('App\Referral', 'referrable');
    }

}
