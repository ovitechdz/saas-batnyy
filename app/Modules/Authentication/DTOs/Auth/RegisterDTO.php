<?php

namespace App\Modules\Authentication\DTOs\Auth;

class RegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $farm_name,
        public readonly ?string $phone = null,
        public readonly ?string $subscription_tier = 'basic',
        public readonly ?string $country_id = null,
        public readonly ?string $ip_address = null,
        public readonly ?string $user_agent = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            farm_name: $data['farm_name'],
            phone: $data['phone'] ?? null,
            subscription_tier: $data['subscription_tier'] ?? 'basic',
            country_id: $data['country_id'] ?? null,
            ip_address: $data['ip_address'] ?? null,
            user_agent: $data['user_agent'] ?? null
        );
    }
}
