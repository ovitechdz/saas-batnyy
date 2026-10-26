<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalNote extends Model
{
    protected $fillable = [
        'animal_id',
        'created_by',
        'note',
    ];

    //
}
