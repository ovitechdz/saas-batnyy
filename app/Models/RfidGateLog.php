<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidGateLog extends Model
{
    protected $fillable = [
        'gate_id',
        'rfid_tag_id',
        'direction',
        'crossed_at',
    ];

    //
}
