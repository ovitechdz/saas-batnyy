<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyLog extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'solar_panel_id',
        'farm_id',
        'energy_produced_kwh',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'energy_produced_kwh' => 'decimal:4',
    ];

    public function solarPanel()
    {
        return $this->belongsTo(SolarPanel::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
