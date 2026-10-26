<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidDevice extends Model
{
    protected $fillable = [
        'farm_id',
        'name',
        'mac_address',
        'ip_address',
        'status',
        'last_ping_at',
    ];

    //
}
