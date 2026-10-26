<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmAsset extends Model
{
    protected $fillable = [
        'farm_id',
        'asset_name',
        'asset_type',
        'purchase_date',
        'purchase_price',
        'salvage_value',
        'useful_life_years',
        'current_value',
        'status',
    ];

    //
}
