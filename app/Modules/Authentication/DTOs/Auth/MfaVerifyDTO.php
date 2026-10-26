<?php

namespace App\Modules\Authentication\DTOs\Auth;

class MfaVerifyDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly string $code,
        public readonly ?string $ip_address = null,
        public readonly ?string $user_agent = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            code: $data['code'],
            ip_address: $data['ip_address'] ?? null,
            user_agent: $data['user_agent'] ?? null
        );
    }
}
