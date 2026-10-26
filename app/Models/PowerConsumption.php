<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerConsumption extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;
    protected $table = 'power_consumption';

    protected $fillable = [
        'farm_id',
        'consumer_type',
        'consumer_id',
        'energy_consumed_kwh',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'energy_consumed_kwh' => 'decimal:4',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
