<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalDeath extends Model
{
    protected $fillable = [
        'animal_id',
        'death_date',
        'cause_of_death',
        'notes',
    ];

    //
}
