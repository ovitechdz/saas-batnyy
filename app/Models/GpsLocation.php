<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsLocation extends Model
{
    protected $fillable = [
        'farm_id',
        'trackable_id',
        'trackable_type',
        'latitude',
        'longitude',
        'recorded_at',
    ];

    //
}
