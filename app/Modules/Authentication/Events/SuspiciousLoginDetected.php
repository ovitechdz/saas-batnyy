<?php

namespace App\Modules\Authentication\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuspiciousLoginDetected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public string $ipAddress,
        public string $userAgent,
        public string $reason
    ) {}
}
