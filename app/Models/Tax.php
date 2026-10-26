<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'farm_id',
        'name',
        'rate',
    ];

    //
}
