<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'sheep_id',
        'vaccine_type',
        'batch_number',
        'administered_date',
        'next_due_date',
        'administered_by',
    ];

    public function sheep()
    {
        return $this->belongsTo(Sheep::class);
    }

    public function administrator()
    {
        return $this->belongsTo(User::class, 'administered_by');
    }
}
