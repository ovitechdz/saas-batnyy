<?php

namespace App\Modules\Authentication\DTOs\Auth;

class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool $remember = false,
        public readonly ?string $device_id = null,
        public readonly ?string $ip_address = null,
        public readonly ?string $user_agent = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            remember: $data['remember'] ?? false,
            device_id: $data['device_id'] ?? null,
            ip_address: $data['ip_address'] ?? null,
            user_agent: $data['user_agent'] ?? null
        );
    }
}
