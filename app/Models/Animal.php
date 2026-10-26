<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'animals';

    protected $fillable = [
        'farm_id',
        'internal_id',
        'visual_tag',
        'electronic_tag',
        'qr_code',
        'name',
        'species_id',
        'breed_id',
        'category_id',
        'status_id',
        'gender',
        'color',
        'horn_status',
        'wool_type',
        'birth_date',
        'birth_weight',
        'body_condition_score',
        'current_market_value',
        'origin',
        'insurance_policy',
        'mother_id',
        'father_id',
        'pen_id',
        'barn_id',
        'feed_group_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'birth_weight' => 'decimal:2',
        'body_condition_score' => 'decimal:1',
        'current_market_value' => 'decimal:2',
    ];

    // Core Relationships
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function species()
    {
        return $this->belongsTo(AnimalSpecies::class, 'species_id');
    }

    public function breed()
    {
        return $this->belongsTo(AnimalBreed::class, 'breed_id');
    }

    public function category()
    {
        return $this->belongsTo(AnimalCategory::class, 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(AnimalStatus::class, 'status_id');
    }

    public function pen()
    {
        return $this->belongsTo(Pen::class);
    }

    public function barn()
    {
        return $this->belongsTo(FarmBarn::class, 'barn_id');
    }

    // Pedigree
    public function mother()
    {
        return $this->belongsTo(Animal::class, 'mother_id');
    }

    public function father()
    {
        return $this->belongsTo(Animal::class, 'father_id');
    }

    public function offspring()
    {
        return $this->hasMany(Animal::class, 'mother_id')->orWhere('father_id', $this->id);
    }

    // Details & Events
    public function photos()
    {
        return $this->hasMany(AnimalPhoto::class);
    }

    public function weights()
    {
        return $this->hasMany(AnimalWeight::class);
    }

    public function movements()
    {
        return $this->hasMany(AnimalMovement::class);
    }

    public function sales()
    {
        return $this->hasMany(AnimalSale::class);
    }

    public function purchases()
    {
        return $this->hasMany(AnimalPurchase::class);
    }

    public function deaths()
    {
        return $this->hasMany(AnimalDeath::class);
    }

    public function births() // As a mother
    {
        return $this->hasMany(AnimalBirth::class, 'mother_id');
    }

    public function notes()
    {
        return $this->hasMany(AnimalNote::class);
    }

    public function documents()
    {
        return $this->hasMany(AnimalDocument::class);
    }

    // Virtual or Aliased for legacy/health
    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'animal_id');
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class, 'animal_id');
    }

    public function breedingCycles()
    {
        return $this->hasMany(BreedingCycle::class, 'animal_id');
    }

    public function gpsLocations()
    {
        return $this->morphMany(GpsLocation::class, 'trackable');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'animal_id');
    }

    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class, 'animal_id');
    }

    public function quarantineRecords()
    {
        return $this->hasMany(QuarantineRecord::class, 'animal_id');
    }

    public function labResults()
    {
        return $this->hasManyThrough(LabResult::class, MedicalRecord::class, 'animal_id', 'medical_record_id');
    }

    public function heatCycles()
    {
        return $this->hasMany(BreedingCycle::class, 'animal_id');
    }

    public function pregnancyRecords()
    {
        return $this->hasMany(PregnancyRecord::class, 'animal_id');
    }

    public function pedigree()
    {
        return $this->belongsTo(Animal::class, 'mother_id'); // Dummy fallback
    }

    // Helper Attributes
    public function getAgeAttribute()
    {
        if (!$this->birth_date) return __('Unknown');
        $birthDate = \Carbon\Carbon::parse($this->birth_date);
        $diff = $birthDate->diff(now());
        if ($diff->y > 0) return "{$diff->y} " . __('yrs') . ($diff->m > 0 ? " {$diff->m} " . __('mo') : "");
        if ($diff->m > 0) return "{$diff->m} " . __('mo');
        return "{$diff->d} " . __('days');
    }

    public function getPrimaryPhotoUrlAttribute()
    {
        $primary = $this->photos()->where('is_primary', true)->first();
        if ($primary) {
            return asset('storage/' . $primary->photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->internal_id ?? 'A') . '&background=10b981&color=fff';
    }
}
