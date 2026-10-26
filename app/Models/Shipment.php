<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasUuids;

    protected $fillable = [
        'farm_id', 'vehicle_id', 'reference', 'origin', 'destination',
        'driver_name', 'driver_phone', 'animal_count', 'animal_type',
        'total_weight_kg', 'distance_km', 'status',
        'scheduled_at', 'departed_at', 'arrived_at', 'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'departed_at'  => 'datetime',
        'arrived_at'   => 'datetime',
    ];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public static function generateReference(): string
    {
        $prefix = 'SHIP-' . now()->format('Ymd') . '-';
        $count = static::whereDate('created_at', today())->count() + 1;
        return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
