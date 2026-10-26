<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $farm = $user->farm;

        return view('settings.index', compact('farm'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:users,email,'.auth()->id(),
            'user_phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
            'weight_unit' => 'required|string|in:kg,lbs',
            'farm_focus' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = auth()->user();
        $farm = $user->farm;

        if ($farm) {
            $settings = $farm->settings ?? [];
            $settings['location'] = $request->location;
            $settings['currency'] = $request->currency;
            $settings['weight_unit'] = $request->weight_unit;
            $settings['farm_focus'] = $request->farm_focus;
            $settings['dark'] = $request->input('dark') == '1';
            $settings['email_notif'] = $request->input('email_notif') == '1';
            $settings['ai_insights'] = $request->input('ai_insights') == '1';

            $farm->update([
                'name' => $request->name,
                'settings' => $settings,
            ]);
        }

        $user->update([
            'name' => $request->user_name,
            'email' => $request->user_email,
            'phone' => $request->user_phone,
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path && \Storage::disk('public')->exists($user->profile_photo_path)) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->update(['profile_photo_path' => $path]);
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
