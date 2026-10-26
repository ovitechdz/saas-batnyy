<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionLog extends Model
{
    protected $fillable = [
        'production_batch_id',
        'raw_material_id',
        'quantity_used',
    ];

    //
}
