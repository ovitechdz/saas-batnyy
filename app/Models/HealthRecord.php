<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'sheep_id',
        'vet_id',
        'diagnosis',
        'treatment',
        'treatment_cost',
        'next_checkup_date',
        'medications',
        'date_recorded',
    ];

    protected $casts = [
        'date_recorded' => 'date',
        'next_checkup_date' => 'date',
        'treatment_cost' => 'decimal:2',
    ];

    public function sheep()
    {
        return $this->belongsTo(Sheep::class);
    }

    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id');
    }
}
