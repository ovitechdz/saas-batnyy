<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IotSensor extends Model
{
    protected $fillable = [
        'device_id',
        'sensor_type',
        'unit_of_measurement',
        'safe_min',
        'safe_max',
    ];

    //
}
