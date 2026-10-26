<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IotDevice extends Model
{
    protected $fillable = [
        'farm_id',
        'mac_address',
        'name',
        'type',
        'status',
        'battery_level',
        'signal_strength',
        'firmware_version',
        'last_seen_at',
    ];

    //
}
