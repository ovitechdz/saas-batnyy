<?php

namespace App\Modules\Authentication\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TokenService
{
    private string $key;
    private string $algorithm = 'HS256';

    public function __construct()
    {
        $this->key = config('app.key'); // Or a specific JWT secret
    }

    public function generateToken(User $user, int $ttlMinutes = 60): string
    {
        $issuedAt = Carbon::now();
        $expireAt = $issuedAt->copy()->addMinutes($ttlMinutes);

        $payload = [
            'iat' => $issuedAt->timestamp,
            'exp' => $expireAt->timestamp,
            'sub' => $user->id,
            'email' => $user->email,
            'jti' => Str::uuid()->toString(),
        ];

        return JWT::encode($payload, $this->key, $this->algorithm);
    }

    public function decodeToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->key, $this->algorithm));
        } catch (\Exception $e) {
            return null;
        }
    }
}
