<?php

namespace App\Modules\Authentication\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // Standard Laravel reset or custom logic
        return back()->with('status', 'We have emailed your password reset link!');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        // Reset logic
        return redirect()->route('login')->with('status', 'Password has been reset successfully.');
    }
}
