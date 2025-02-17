<?php

namespace App\Repo\Admin\AdminManagement;

use App\Models\Admin;

class AdminManagementRepo
{
    public function index() : mixed
    {
        return Admin::searchAdmin()
        ->paginate(10);
    }

    public function action(array $data) : Admin
    {
        $admin = Admin::updateOrCreate(
            ['id' => $data['action'] == 'update' ? $data['admin_id'] : null],
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]
        );

        // if action was update , delete all roles and assign new roles
        if($data['action'] == 'update'){
            $admin->roles()->detach();
        }

        $admin->syncRoles($data['role_id']);

        return $admin;
    }

    public function delete(int $admin_id) : void
    {
        Admin::destroy($admin_id);
    }
}
