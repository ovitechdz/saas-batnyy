<?php

namespace App\Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;

class LoginHistory extends Model
{
    use HasUuids;

    protected $table = 'login_history';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'success',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
