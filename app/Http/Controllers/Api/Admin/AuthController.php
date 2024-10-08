<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(LoginRequest $request): JsonResponse
    {
        $process =  new \App\Services\Auth\Authentication($request);


        return $process->returnResponse('api')->signIn('admin', '');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $process =  new \App\Services\Auth\Authentication($request);

        return $process->returnResponse('api')->changePassword((new Admin), $request);
    }

    public function logout(Request $request): JsonResponse
    {
        $process =  new \App\Services\Auth\Authentication($request);

        return $process->returnResponse('api')->signOut();
    }
}
