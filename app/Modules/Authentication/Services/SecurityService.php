<?php

namespace App\Modules\Authentication\Services;

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

class SecurityService
{
    public function checkRateLimit(string $email, string $ipAddress): void
    {
        $key = 'login:' . $email . ':' . $ipAddress;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many login attempts. Please try again later.');
        }
    }

    public function recordFailedAttempt(string $email, string $ipAddress): void
    {
        $key = 'login:' . $email . ':' . $ipAddress;
        RateLimiter::hit($key, 60 * 15); // 15 minutes lockout
    }

    public function clearFailedAttempts(string $email, string $ipAddress): void
    {
        $key = 'login:' . $email . ':' . $ipAddress;
        RateLimiter::clear($key);
    }

    public function checkSuspiciousLogin(User $user, string $ipAddress, string $userAgent): void
    {
        // Compare with known devices/IPs
        // Fire SuspiciousLoginDetected event if unknown
    }
}
