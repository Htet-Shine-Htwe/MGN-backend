<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enum\AdminRole;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\RolePermissions\AlphaRole;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct(public AlphaRole $alphaRole)
    {

    }

    public function roles(Request $request)
    {
        $roles = $this->alphaRole->setGuard('admin')->getRoles();

        return response()->json(
            [
            'roles' => $roles
            ]
        );
    }

    public function createRole(Request $request)
    {
        $request->validate(
            [
            'name' => 'required|string',
            'permissions' => 'array'
            ]
        );

        $role = $this->alphaRole->setGuard('admin')->createRole($request->name, $request->permissions);

        return response()->json(
            [
            'role' => $role,
            'message' => 'Role created successfully',
            ], 201
        );
    }

    public function permissions(Request $request)
    {
        $permissions = $this->alphaRole->setGuard('admin')->getPermissions();

        return response()->json(
            [
            'permissions' => $permissions
            ]
        );
    }

    public function members(Request $request)
    {
        $members =  Admin::with('roles')->get();

        return response()->json(
            [
            'members' => $members
            ]
        );
    }
}
