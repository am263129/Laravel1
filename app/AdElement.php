<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdElement extends Model
{
    protected $fillable = [
        'current_id',
        'place',
        'root_class',
        'ins_class',
        'data_ad_client',
        'data_ad_slot',
        'data_ad_layout_key',
        'data_ad_test',
        'data_ad_format',
        'active',
        'data_ad_full_width_responsive',
        'is_non_personalized_ad',
    ];
}
