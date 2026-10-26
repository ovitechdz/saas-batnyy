<?php

namespace App\Modules\Authentication\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Authentication\DTOs\Auth\LoginDTO;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(private AuthenticationService $authService) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $dto = LoginDTO::fromArray([
            'email' => $request->email,
            'password' => $request->password,
            'remember' => $request->boolean('remember'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_id' => $request->cookie('device_id'),
        ]);

        $result = $this->authService->login($dto);

        // Standard session login for Web
        Auth::login($result['user'], $dto->remember);

        if ($result['requires_mfa']) {
            return redirect()->route('mfa.challenge');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
