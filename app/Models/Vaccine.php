<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    protected $fillable = [
        'farm_id',
        'name',
        'target_disease',
        'booster_days',
    ];

    //
}
