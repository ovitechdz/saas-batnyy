<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolarReading extends Model
{
    protected $fillable = [
        'panel_id',
        'energy_produced_kwh',
        'reading_time',
    ];

    //
}
