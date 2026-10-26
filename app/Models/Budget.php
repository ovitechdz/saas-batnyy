<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'farm_id',
        'category',
        'amount',
        'start_date',
        'end_date',
    ];

    //
}
