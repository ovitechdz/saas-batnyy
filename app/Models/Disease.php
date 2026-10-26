<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $fillable = [
        'name',
        'scientific_name',
        'description',
        'is_contagious',
    ];

    //
}
