<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductionBatch extends Model
{
    use HasUuids;
    protected $fillable = [
        'farm_id',
        'batch_number',
        'concentrate_id',
        'yield_quantity',
        'unit',
        'production_date',
    ];

    //
}
