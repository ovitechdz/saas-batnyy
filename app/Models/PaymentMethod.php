<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'farm_id',
        'name',
        'is_active',
    ];

    //
}
