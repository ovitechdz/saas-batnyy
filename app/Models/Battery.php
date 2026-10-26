<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'farm_id',
        'name',
        'capacity_kwh',
        'current_charge_percentage',
        'status',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
