<?php

namespace App\Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;

class Device extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'device_id',
        'browser',
        'platform',
        'device_type',
        'ip_address',
        'is_trusted',
        'last_active_at',
    ];

    protected $casts = [
        'is_trusted' => 'boolean',
        'last_active_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
