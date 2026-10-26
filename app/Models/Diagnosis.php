<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'animal_id',
        'condition',
        'notes',
        'diagnosis_date',
    ];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
