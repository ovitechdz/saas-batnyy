<?php

namespace App\Modules\Authentication\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(string $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create([
            'id' => Str::uuid(),
            'farm_id' => $data['farm_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'is_super_admin' => $data['is_super_admin'] ?? false,
        ]);
    }

    public function updatePassword(User $user, string $newPassword): bool
    {
        return $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public function assignRole(User $user, string $roleId): void
    {
        $user->roles()->syncWithoutDetaching([$roleId]);
    }
}
