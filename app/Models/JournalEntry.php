<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'farm_id',
        'reference_number',
        'entry_date',
        'description',
        'status',
    ];

    public function lines()
    {
        return $this->hasMany(JournalLine::class);
    }
}
