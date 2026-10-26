<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreedingCycle extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'animal_id',
        'ram_id',
        'breeding_method',
        'mating_type',
        'bcs_at_mating',
        'start_date',
        'expected_birth_date',
        'scan_date',
        'pregnancy_status',
        'fetus_count',
        'notes',
        'status',
    ];

    public function ewe()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    public function ram()
    {
        return $this->belongsTo(Animal::class, 'ram_id');
    }
}
