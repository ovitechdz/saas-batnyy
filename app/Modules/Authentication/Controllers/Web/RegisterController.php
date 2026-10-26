<?php

namespace App\Modules\Authentication\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Authentication\DTOs\Auth\RegisterDTO;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(private AuthenticationService $authService) {}

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'farm_name' => 'required|string|max:255',
            'terms' => 'accepted'
        ]);

        $dto = RegisterDTO::fromArray([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'farm_name' => $request->farm_name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $user = $this->authService->register($dto);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
