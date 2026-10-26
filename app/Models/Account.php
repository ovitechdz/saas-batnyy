<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'farm_id',
        'name',
        'account_type',
        'balance',
        'currency_id',
    ];

    //
}
