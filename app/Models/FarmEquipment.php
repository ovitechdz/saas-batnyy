<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmEquipment extends Model
{
    protected $fillable = [
        'id', 'farm_id', 'zone_id', 'name', 'serial_number', 'type', 'power_type', 'fuel_level', 'next_maintenance_date', 'status'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
