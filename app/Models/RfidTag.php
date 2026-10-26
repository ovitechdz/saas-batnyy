<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidTag extends Model
{
    protected $fillable = [
        'farm_id',
        'animal_id',
        'epc_code',
        'tag_type',
        'assigned_date',
        'status',
    ];

    //
}
