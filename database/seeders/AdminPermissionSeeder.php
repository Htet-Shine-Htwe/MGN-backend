<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            'admin',
            'uploader',
        ];

        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create(['name' => $role, 'guard_name' => 'admin']);
        }

        $parent_permissions = [
            'dashboard',
            'comics',
            'categories',
            'subscriptions',
            'users',
            'apps',
            'setting',
            'report',
            'admins'
        ];

        $adminPermissions = $parent_permissions;

        $uploaderPermissions = [
            'categories',
            'dashboard',
            'comics',
            'apps',
            'report',
        ];

        foreach ($parent_permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        \Spatie\Permission\Models\Role::where('name', 'admin')->first()->syncPermissions($adminPermissions);
        \Spatie\Permission\Models\Role::where('name', 'uploader')->first()->syncPermissions($uploaderPermissions);

        if(Admin::count() > 0){
            Admin::where('email', 'admin@gmail.com')->first()->assignRole('admin');

            Admin::where('email', '!=','admin@gmaill.com')->each(function($admin){
                $admin->assignRole('uploader');
            });
        }

    }
}
