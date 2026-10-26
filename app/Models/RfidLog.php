<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidLog extends Model
{
    protected $fillable = [
        'rfid_tag_id',
        'antenna_id',
        'rssi',
        'read_at',
    ];

    //
}
