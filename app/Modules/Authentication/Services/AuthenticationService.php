<?php

namespace App\Modules\Authentication\Services;

use App\Models\User;
use App\Modules\Authentication\DTOs\Auth\LoginDTO;
use App\Modules\Authentication\DTOs\Auth\RegisterDTO;
use App\Modules\Authentication\Repositories\UserRepository;
use App\Modules\Authentication\Repositories\AuditLogRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticationService
{
    public function __construct(
        private UserRepository $userRepository,
        private AuditLogRepository $auditLogRepository,
        private TokenService $tokenService,
        private SecurityService $securityService
    ) {}

    public function login(LoginDTO $dto): array
    {
        // 1. Check Rate Limiting
        $this->securityService->checkRateLimit($dto->email, $dto->ip_address);

        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            $this->securityService->recordFailedAttempt($dto->email, $dto->ip_address);
            $this->auditLogRepository->logLoginAttempt($user?->id ?? 'unknown', $dto->ip_address, $dto->user_agent, 'failed', $dto->device_id);
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials provided.'],
            ]);
        }

        // 2. Check Suspicious Login (Device/IP)
        $this->securityService->checkSuspiciousLogin($user, $dto->ip_address, $dto->user_agent);

        // 3. Clear failed attempts
        $this->securityService->clearFailedAttempts($dto->email, $dto->ip_address);

        // 4. Log successful attempt
        $this->auditLogRepository->logLoginAttempt($user->id, $dto->ip_address, $dto->user_agent, 'success', $dto->device_id);

        // 5. Generate JWT Token
        $token = $this->tokenService->generateToken($user);

        return [
            'user' => $user,
            'token' => $token,
            'requires_mfa' => false, // To be integrated with MfaService
        ];
    }

    public function register(RegisterDTO $dto): User
    {
        // In a real flow, this creates the Farm first via FarmRepository, then User.
        // For brevity, we pass farm_id null and it gets updated later or via event.
        $user = $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'phone' => $dto->phone,
        ]);

        return $user;
    }
}
