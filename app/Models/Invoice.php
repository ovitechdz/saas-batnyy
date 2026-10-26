<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'farm_id',
        'invoice_number',
        'type',
        'entity_id',
        'entity_type',
        'issue_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount',
        'total',
        'status',
    ];

    //
}
