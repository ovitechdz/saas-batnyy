<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuarantineRecord extends Model
{
    protected $fillable = [
        'animal_id',
        'reason',
        'start_date',
        'end_date',
        'isolation_area',
        'status',
        'recorded_by',
    ];

    //
}
