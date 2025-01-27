<?php

namespace App\Services\RolePermissions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Contracts\Role as RoleContract;

class AlphaRole
{
    protected string $guard = "web";

    public function __construct()
    {
    }

    public function setGuard(string $guard): AlphaRole
    {
        $this->guard = $guard;
        return $this;
    }

    /**
     * Get roles.
     *
     * @return Collection<int, \stdClass>
     */
    public function getRoles(): Collection
    {
        // Assuming DB::table()->get() returns a collection of objects.
        return DB::table('roles')
            ->where('guard_name', $this->guard)
            ->get();
    }

    /**
     * Get permissions.
     *
     * @return Collection<int, \stdClass>
     */
    public function getPermissions(): Collection
    {
        // Assuming DB::table()->get() returns a collection of objects.
        return DB::table('permissions')
            ->where('guard_name', $this->guard)
            ->get();
    }

    /**
     * Create a role.
     *
     * @param  array<string>|string $roles
     * @param  array<string>|null $permissions
     * @return Collection<int, RoleContract>|RoleContract
     */
    public function createRole(array|string $roles, ?array $permissions = null): Collection|RoleContract
    {
        $collection = [];

        if (is_array($roles)) {
            foreach ($roles as $role) {
                $new_role = Role::create(['name' => $role, 'guard_name' => $this->guard]);
                if ($permissions) {
                    $new_role->syncPermissions(permissions: $permissions);
                }
                $collection[] = $new_role;
            }

            return collect($collection); // Returns a Collection of Roles.
        } else {
            $new_role = Role::create(['name' => $roles, 'guard_name' => $this->guard]);

            if ($permissions) {
                $new_role->syncPermissions($permissions);
            }

            return $new_role; // Return a single Role instance.
        }
    }
}
