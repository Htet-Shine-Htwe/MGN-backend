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
            'admins',
            'users',
            'content-management',
            'content-genre',
            'social-media',
            'reports',
        ];

        foreach ($parent_permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        if(Admin::count() > 0){
            Admin::where('email', 'admin@gmail.com')->first()->assignRole('admin');

            Admin::where('email', '!=','admin@gmaill.com')->each(function($admin){
                $admin->assignRole('uploader');
            });
        }

    }
}
