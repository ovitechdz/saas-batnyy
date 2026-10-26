<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Authentication\Models\Role;
use App\Modules\Authentication\Models\Device;
use App\Modules\Authentication\Models\LoginHistory;
use App\Modules\Authentication\Models\ApiToken;
use App\Modules\Authentication\Models\MfaCode;
use App\Modules\Authentication\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, SoftDeletes, HasRoles;

    protected $fillable = [
        'farm_id',
        'name',
        'email',
        'phone',
        'password',
        'is_super_admin',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? url('media/' . $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0f172a&color=fff';
    }

    // Relationships
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function mfaCodes()
    {
        return $this->hasMany(MfaCode::class);
    }

    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class);
    }
}
