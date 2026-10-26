<?php

namespace App\Modules\Authentication\Listeners;

use App\Modules\Authentication\Events\SuspiciousLoginDetected;
use Illuminate\Support\Facades\Log;

class SendSuspiciousLoginAlert
{
    public function handle(SuspiciousLoginDetected $event): void
    {
        Log::warning('Suspicious Login Detected', [
            'user_id' => $event->user->id,
            'ip' => $event->ipAddress,
            'reason' => $event->reason,
        ]);

        // Trigger email notification to user
    }
}
