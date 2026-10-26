<?php

namespace App\Modules\Authentication\Repositories;

use App\Modules\Authentication\Models\LoginHistory;

class AuditLogRepository
{
    public function logLoginAttempt(string $userId, string $ipAddress, string $userAgent, string $status, ?string $deviceId = null): LoginHistory
    {
        return LoginHistory::create([
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'success' => $status === 'success',
        ]);
    }

    public function getRecentLogins(string $userId, int $limit = 10)
    {
        return LoginHistory::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
