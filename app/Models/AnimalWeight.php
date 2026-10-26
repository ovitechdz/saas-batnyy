<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalWeight extends Model
{
    protected $fillable = [
        'animal_id',
        'weight_kg',
        'measurement_date',
    ];

    //
}
