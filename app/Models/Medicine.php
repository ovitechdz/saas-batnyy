<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'farm_id',
        'name',
        'active_ingredient',
        'manufacturer',
        'withdrawal_period_days',
    ];

    //
}
