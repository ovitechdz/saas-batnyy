<?php

namespace App\Modules\Authentication\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Authentication\Models\Device;
use Illuminate\Support\Str;

class DeviceDetector
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $userAgent = $request->userAgent();
            $ipAddress = $request->ip();
            $deviceId = $request->cookie('device_id');

            if (!$deviceId) {
                // New Device
                $deviceId = Str::uuid()->toString();
                // We should set the cookie on the response, but we need the response object first
            }

            // Record or update device
            Device::updateOrCreate(
                ['user_id' => $request->user()->id, 'device_id' => $deviceId],
                [
                    'browser' => $this->parseBrowser($userAgent),
                    'platform' => $this->parsePlatform($userAgent),
                    'ip_address' => $ipAddress,
                    'last_active_at' => now(),
                ]
            );
        }

        $response = $next($request);

        if ($request->user() && isset($deviceId) && !$request->cookie('device_id')) {
            $response->cookie('device_id', $deviceId, 60 * 24 * 365); // 1 year
        }

        return $response;
    }

    private function parseBrowser(?string $ua): string
    {
        if (!$ua) return 'Unknown';
        if (str_contains($ua, 'Chrome')) return 'Chrome';
        if (str_contains($ua, 'Firefox')) return 'Firefox';
        if (str_contains($ua, 'Safari')) return 'Safari';
        if (str_contains($ua, 'Edge')) return 'Edge';
        return 'Other';
    }

    private function parsePlatform(?string $ua): string
    {
        if (!$ua) return 'Unknown';
        if (str_contains($ua, 'Windows')) return 'Windows';
        if (str_contains($ua, 'Mac')) return 'macOS';
        if (str_contains($ua, 'Linux')) return 'Linux';
        if (str_contains($ua, 'Android')) return 'Android';
        if (str_contains($ua, 'iPhone')) return 'iOS';
        return 'Other';
    }
}
