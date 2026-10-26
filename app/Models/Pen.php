<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pen extends Model
{
    protected $fillable = [
        'farm_id',
        'zone_id',
        'name',
        'capacity',
    ];

    //
}
