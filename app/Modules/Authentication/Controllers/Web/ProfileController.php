<?php

namespace App\Modules\Authentication\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    public function settings(Request $request)
    {
        return view('profile.settings', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        // Update user profile
        return back()->with('status', 'Profile updated successfully.');
    }
}
