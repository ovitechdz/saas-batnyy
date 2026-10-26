<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'farm_id',
        'animal_id',
        'visit_id',
        'examination_date',
        'temperature',
        'heart_rate',
        'respiratory_rate',
        'notes',
    ];

    protected $casts = [
        'examination_date' => 'date',
        'temperature' => 'decimal:2',
        'heart_rate' => 'integer',
        'respiratory_rate' => 'integer',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function visit()
    {
        return $this->belongsTo(VeterinaryVisit::class, 'visit_id');
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class);
    }
}
