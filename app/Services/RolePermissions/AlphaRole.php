<?php

namespace App\Services\RolePermissions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

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

    public function getRoles() : Collection
    {
        return DB::table('roles')->where('guard_name', $this->guard)->get();
    }

    public function getPermissions() : Collection
    {
        return DB::table('permissions')->where('guard_name', $this->guard)->get();
    }

    public function createRole(array|string $roles,?array $permissons = null ):  Collection|Role
    {
        $collection = [];
        if(is_array($roles)) {
            foreach($roles as $role)
            {
                $new_role = Role::create(['name' => $role, 'guard_name' => $this->guard]);
                if($permissons) {
                    $new_role->syncPermissions($permissons);
                }
                $collection[] = $new_role;
            }
            $collection = collect($collection);
        }
        else{
            $new_role = Role::create(['name' => $roles, 'guard_name' => $this->guard]);

            if($permissons) {
                $new_role->syncPermissions($permissons);
            }

            $collection = $new_role;
        }

        return $collection;
    }
}
