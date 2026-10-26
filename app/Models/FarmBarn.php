<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmBarn extends Model
{
    protected $fillable = [
        'id', 'farm_id', 'zone_id', 'name', 'type', 'capacity', 'current_occupancy', 'temperature', 'humidity', 'status'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function zone()
    {
        return $this->belongsTo(FarmZone::class, 'zone_id');
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
