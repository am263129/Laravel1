<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recently_watched extends Model
{
    use Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
