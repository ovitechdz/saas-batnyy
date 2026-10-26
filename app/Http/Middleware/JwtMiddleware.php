<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token not provided'], 401);
        }

        try {
            $secret = env('APP_KEY', 'default_secret');
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($secret, 'HS256'));
            
            // Get user from database based on the 'sub' claim
            $user = \App\Models\User::find($decoded->sub);
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            // Authenticate the user for the current request lifecycle
            auth()->setUser($user);
            
        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json(['success' => false, 'message' => 'Provided token is expired'], 401);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error while decoding token', 'error' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
