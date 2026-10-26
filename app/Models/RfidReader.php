<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidReader extends Model
{
    protected $fillable = [
        'device_id',
        'reader_model',
        'firmware_version',
    ];

    //
}
