<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sheep extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'farm_id',
        'rfid_tag',
        'qr_code',
        'name',
        'gender',
        'breed_id',
        'birth_date',
        'status',
        'mother_id',
        'father_id',
        'weight',
        'source',
        'barn_number',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

    public function breedingCycles()
    {
        return $this->hasMany(BreedingCycle::class, 'ewe_id');
    }
}
