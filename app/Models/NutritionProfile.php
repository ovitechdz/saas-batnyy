<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionProfile extends Model
{
    protected $fillable = [
        'feed_inventory_id',
        'protein_percentage',
        'energy_mcal',
        'fiber_percentage',
        'calcium_percentage',
        'phosphorus_percentage',
    ];

    //
}
