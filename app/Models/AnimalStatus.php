<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalStatus extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $table = 'animal_status';
    protected $guarded = [];
}
