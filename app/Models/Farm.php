<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'registration_number',
        'country_id',
        'currency_id',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function getLocationAttribute()
    {
        return $this->settings['location'] ?? null;
    }

    public function getCurrencyAttribute()
    {
        return $this->settings['currency'] ?? 'USD';
    }

    public function getWeightUnitAttribute()
    {
        return $this->settings['weight_unit'] ?? 'kg';
    }

    public function getFarmFocusAttribute()
    {
        return $this->settings['farm_focus'] ?? null;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function sheep()
    {
        return $this->hasMany(Sheep::class);
    }
}
