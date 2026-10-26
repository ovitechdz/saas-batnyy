<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSource extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'farm_id',
        'name',
        'type',
        'capacity',
        'status',
        'zone_id',
        'flow_rate',
        'depth',
        'coordinates',
        'installation_date',
        'next_maintenance_date',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'next_maintenance_date' => 'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function zone()
    {
        return $this->belongsTo(FarmZone::class);
    }

    public function readings()
    {
        return $this->hasMany(WaterReading::class)->orderBy('reading_date', 'desc');
    }
}
