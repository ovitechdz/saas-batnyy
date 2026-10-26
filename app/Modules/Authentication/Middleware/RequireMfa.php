<?php

namespace App\Modules\Authentication\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireMfa
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user has MFA enabled and hasn't verified this session
        if ($user && $user->mfa_enabled && !$request->session()->has('mfa_verified')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'MFA verification required.'], 403);
            }
            return redirect()->route('mfa.challenge');
        }

        return $next($request);
    }
}
