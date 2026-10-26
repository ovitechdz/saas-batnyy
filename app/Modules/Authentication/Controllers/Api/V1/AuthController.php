<?php

namespace App\Modules\Authentication\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Authentication\DTOs\Auth\LoginDTO;

class AuthController extends Controller
{
    public function __construct(private AuthenticationService $authService) {}

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $dto = LoginDTO::fromArray([
            'email' => $request->email,
            'password' => $request->password,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_id' => $request->header('X-Device-Id'),
        ]);

        try {
            $result = $this->authService->login($dto);
            
            return response()->json([
                'success' => true,
                'token' => $result['token'],
                'requires_mfa' => $result['requires_mfa'],
                'user' => $result['user'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        // Typically handled by expiring token or maintaining blacklist
        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }
}
