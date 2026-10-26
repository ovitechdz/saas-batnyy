<?php

namespace App\Modules\Authentication\Services;

class SocialLoginService
{
    // Handles OAuth responses from Google, Microsoft, Apple
    public function handleProviderCallback(string $provider, array $userData)
    {
        // 1. Check if user exists by email
        // 2. If not, create User
        // 3. Link provider ID
        // 4. Return User
    }
}
