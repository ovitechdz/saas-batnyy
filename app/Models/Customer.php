<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasUuids;

    protected $fillable = [
        'farm_id', 'name', 'first_name', 'last_name', 'phone', 'whatsapp', 'email', 'type',
        'company', 'city', 'country', 'address', 'notes',
        'status', 'total_revenue', 'total_orders', 'last_purchase_at',
        'photo_path',
    ];

    protected $casts = [
        'last_purchase_at' => 'datetime',
        'total_revenue'    => 'decimal:2',
    ];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', trim($this->name));
        return mb_strtoupper(mb_substr($parts[0], 0, 1) . (count($parts) > 1 ? mb_substr(end($parts), 0, 1) : ''));
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0ea5e9&color=fff&size=128';
    }
}
