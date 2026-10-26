<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetDepreciation extends Model
{
    protected $fillable = [
        'farm_asset_id',
        'depreciation_date',
        'amount',
        'journal_entry_id',
    ];

    //
}
