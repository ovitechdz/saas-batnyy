<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalMovement extends Model
{
    protected $fillable = [
        'animal_id',
        'from_pen_id',
        'to_pen_id',
        'moved_at',
        'moved_by',
    ];

    //
}
