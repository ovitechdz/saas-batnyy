<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalBirth extends Model
{
    protected $fillable = [
        'mother_id',
        'farm_id',
        'birth_date',
        'litter_size',
        'live_births',
    ];

    //
}
