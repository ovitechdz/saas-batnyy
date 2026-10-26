<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Authentication\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $farmId = auth()->user()->farm_id;
        $staff = User::with('roles')->where('farm_id', $farmId)->get();
        $roles = Role::all();
        
        return view('staff.index', compact('staff', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'new_role_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        $roleId = $request->role_id;
        if ($request->filled('new_role_name')) {
            $otherRole = Role::where('name', 'other')->first();
            if ($otherRole && $otherRole->id == $roleId) {
                $customRole = Role::firstOrCreate(
                    ['name' => strtolower($request->new_role_name)],
                    ['id' => (string) \Illuminate\Support\Str::uuid(), 'description' => ucfirst($request->new_role_name)]
                );
                $roleId = $customRole->id;
            }
        }

        $farmId = auth()->user()->farm_id;

        $user = User::create([
            'farm_id' => $farmId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        $user->roles()->attach($roleId);

        return back()->with('success', 'Staff member added successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting the currently logged-in user
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Only allow deleting users from the same farm
        if ($user->farm_id === auth()->user()->farm_id) {
            $user->delete();
            return back()->with('success', 'Staff member removed successfully.');
        }

        return back()->with('error', 'Unauthorized action.');
    }
}
