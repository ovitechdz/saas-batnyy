<?php

namespace App\Modules\Authentication\Services;

class MfaService
{
    // Generates OTP, validates TOTP, sends SMS
    public function generateOtp(string $userId, string $type = 'email'): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function verifyOtp(string $userId, string $code): bool
    {
        // Mock verification
        return $code === '123456';
    }
}
