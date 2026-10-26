<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $fillable = [
        'farm_id',
        'year',
        'month',
        'total_income',
        'total_expenses',
        'net_cash_flow',
    ];

    protected $table = 'cash_flow';
}
