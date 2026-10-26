<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeterinaryVisit extends Model
{
    protected $fillable = [
        'farm_id',
        'veterinarian_id',
        'visit_date',
        'reason',
        'general_notes',
        'cost',
    ];

    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }
}
