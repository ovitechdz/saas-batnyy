<?php

namespace App\Modules\Authentication\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Authentication\Services\MfaService;

class MfaController extends Controller
{
    public function __construct(private MfaService $mfaService) {}

    public function showChallengeForm()
    {
        return view('auth.mfa');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $user = $request->user();
        if ($this->mfaService->verifyOtp($user->id, $request->code)) {
            $request->session()->put('mfa_verified', true);
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['code' => 'Invalid or expired code.']);
    }
}
