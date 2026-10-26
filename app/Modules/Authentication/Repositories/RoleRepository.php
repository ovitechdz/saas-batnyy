<?php

namespace App\Modules\Authentication\Repositories;

use App\Modules\Authentication\Models\Role;
use App\Modules\Authentication\Models\Permission;

class RoleRepository
{
    public function findRoleBySlug(string $slug): ?Role
    {
        return Role::where('slug', $slug)->first();
    }

    public function createRole(string $name, string $slug, ?string $farmId = null): Role
    {
        return Role::create([
            'name' => $name,
            'slug' => $slug,
            'farm_id' => $farmId,
        ]);
    }

    public function getPermissionsForRole(Role $role)
    {
        return $role->permissions;
    }
}
