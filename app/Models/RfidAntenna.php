<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidAntenna extends Model
{
    protected $fillable = [
        'device_id',
        'port_number',
        'location_description',
        'zone_id',
    ];

    //
}
