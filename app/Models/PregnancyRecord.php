<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PregnancyRecord extends Model
{
    protected $fillable = [
        'breeding_record_id',
        'animal_id',
        'confirmation_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'number_of_fetuses',
        'status',
    ];

    use HasUuids;

    protected $guarded = [];

    public function breedingRecord()
    {
        return $this->belongsTo(BreedingRecord::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
