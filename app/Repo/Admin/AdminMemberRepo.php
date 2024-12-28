<?php

namespace App\Repo\Admin;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Collection;

class AdminMemberRepo
{
    public function __construct()
    {
    }

    /**
     * getMember
     *
     * @param Admin $admin
     * @return Collection<int,Admin>
     */
    public function getMember(Admin $admin): Collection
    {
        return $admin->with('roles')->get();
    }

    public function create(array $data) : Admin
    {
        return Admin::create($data);
    }

    public function update(Admin $admin,array $data): bool
    {
        return $admin->update($data);
    }

    public function delete(Admin $admin): bool|null
    {
        return $admin->delete();
    }


}
