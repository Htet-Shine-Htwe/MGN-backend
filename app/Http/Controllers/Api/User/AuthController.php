<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {

    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $process =  new \App\Services\Auth\Authentication($request);

        return $process->returnResponse('api')->signIn('web', '');
    }

    public function logout(Request $request): JsonResponse
    {
        $process =  new \App\Services\Auth\Authentication($request);

        return $process->returnResponse('api')->signOut();
    }
}
