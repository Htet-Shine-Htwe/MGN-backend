<?php

namespace Tests\Support;
use App\Enum\AdminRole;
use App\Models\User;
use App\Models\Admin;
use Database\Seeders\AdminPermissionSeeder;
use Illuminate\Contracts\Auth\Authenticatable;

trait UserAuthenticated
{
    private User $user;

    private Admin $admin;

    public function setupUser(array $body = [])
    {
        $this->user = User::factory()->create($body);

        return $this->authenticated($this->user);
    }

    public function authenticated(Authenticatable $user = null)
    {
        return $this->actingAs($user ?? $this->user);
    }

    public function setupAdmin(array $body = [])
    {
        $this->admin = Admin::factory()->create($body);
        $this->admin->assignRole(AdminRole::Admin->value);
        return $this->authenticatedAdmin($this->admin);
    }

    public function authenticatedAdmin(Authenticatable $admin = null)
    {
        return $this->actingAs($admin ?? $this->admin);
    }

    public function createOrgAdmin(int $count =1)
    {
        $assistant_admins = Admin::factory()->count($count)->create();

        foreach($assistant_admins as $assistant_admin){
            $assistant_admin->assignRole(AdminRole::Moderator->value);
        }

        return $assistant_admins;
    }
}
