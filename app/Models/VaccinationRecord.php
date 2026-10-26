<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaccinationRecord extends Model
{
    protected $fillable = [
        'animal_id',
        'vaccine_id',
        'administered_date',
        'next_due_date',
        'batch_number',
        'administered_by',
    ];

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }
}
