<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterReading extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'water_source_id',
        'reading_date',
        'consumption',
        'ph_level',
        'salinity',
        'notes',
    ];

    protected $casts = [
        'reading_date' => 'datetime',
    ];

    public function source()
    {
        return $this->belongsTo(WaterSource::class, 'water_source_id');
    }
}
