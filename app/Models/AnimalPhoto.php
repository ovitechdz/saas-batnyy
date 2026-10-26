<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AnimalPhoto extends Model
{
    use HasUuids;

    protected $fillable = [
        'animal_id',
        'photo_path',
        'is_primary',
    ];

    //
}
