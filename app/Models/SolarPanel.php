<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolarPanel extends Model
{
    protected $fillable = [
        'farm_id',
        'name',
        'model',
        'capacity_kw',
        'installation_date',
        'status',
    ];

    //
}
