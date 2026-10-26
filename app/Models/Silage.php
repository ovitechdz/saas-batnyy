<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Silage extends Model
{
    use HasUuids;
    protected $fillable = [
        'pit_name',
        'harvest_date',
        'estimated_tonnage',
        'status',
    ];
}
