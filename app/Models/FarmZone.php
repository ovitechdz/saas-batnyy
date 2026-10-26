<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmZone extends Model
{
    protected $fillable = [
        'id', 'farm_id', 'name', 'type', 'area_size', 'status', 'coordinates', 'soil_type', 'manager_id'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function barns()
    {
        return $this->hasMany(FarmBarn::class, 'zone_id');
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
