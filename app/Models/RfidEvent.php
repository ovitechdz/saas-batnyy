<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidEvent extends Model
{
    protected $fillable = [
        'rfid_tag_id',
        'event_type',
        'event_data',
        'occurred_at',
    ];

    //
}
