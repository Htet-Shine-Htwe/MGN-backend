<?php

namespace App\Http\Controllers\Api\Admins;

use App\Http\Controllers\Controller;
use App\Repo\Admin\AdminManagement\AdminManagementRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminManagementController extends Controller
{

    public function __construct(protected AdminManagementRepo $adminManagementRepo){}

    public function index() : JsonResponse
    {
        $admins = $this->adminManagementRepo->index();

        return response()->json([
            'admins' => $admins
        ], 200);
    }

    public function action(Request $request) : JsonResponse
    {
        $data = $request->validate([
            'action' => 'required|string|in:create,update',
            'admin_id' => 'nullable|integer|exists:admins,id',
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string',
            'email' => $request->action == 'create' ? 'required|email|unique:admins,email' : 'required|email',
            'password' => 'required|string',
        ]);

        $admin = $this->adminManagementRepo->action($data);

        return response()->json([
            'message' => "Admin has been {$data['action']}ed",
            "admin" => $admin
        ], 200);
    }

    public function delete(Request $request) : JsonResponse
    {
        $data = $request->validate([
            'admin_id' => 'required|integer',
        ]);

        if(auth()->id() == $data['admin_id']){
            return response()->json([
                'message' => "You can't delete yourself"
            ], 400);
        }

        $this->adminManagementRepo->delete($data['admin_id']);

        return response()->json([
            'message' => "Admin has been deleted"
        ], 200);
    }



    public function roles() : JsonResponse
    {
        $roles = Role::where('guard_name','admin')->get()->each(function($role){
            $role->name = ucwords($role->name);
            return $role;
        });

        $roles = $roles->toArray();


        return response()->json([
            'roles' => $roles
        ], 200);
    }
}
