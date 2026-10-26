<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BreedingRecord extends Model
{
    protected $fillable = [
        'farm_id',
        'mother_id',
        'father_id',
        'mating_date',
        'method',
        'status',
        'recorded_by',
    ];

    use HasUuids;

    protected $guarded = [];

    public function mother()
    {
        return $this->belongsTo(Animal::class, 'mother_id');
    }

    public function father()
    {
        return $this->belongsTo(Animal::class, 'father_id');
    }

    public function pregnancy()
    {
        return $this->hasOne(PregnancyRecord::class);
    }
}
