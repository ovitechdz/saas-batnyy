<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalDocument extends Model
{
    protected $fillable = [
        'animal_id',
        'title',
        'file_path',
        'document_type',
    ];

    //
}
