<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles =[
            'normal',
            'premium',
        ];

        foreach($roles as $role)
        {
            \Spatie\Permission\Models\Role::create(['name' => $role, 'guard_name' => 'web']);
        }

        $parent_permissions = [
            'premium-content',
            'normal-content',
        ];

        foreach($parent_permissions as $permission)
        {
            \Spatie\Permission\Models\Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
