<?php

namespace App\Repo\Admin;

use App\Models\Admin;

class AdminMemberRepo
{
    public function __construct()
    {
    }

    public function getMember(Admin $admin)
    {
        return $admin->with('roles')->get();
    }

    public function create($data)
    {
        return Admin::create($data);
    }

    public function update(Admin $admin, $data)
    {
        return $admin->update($data);
    }

    public function delete(Admin $admin)
    {
        return $admin->delete();
    }


}
