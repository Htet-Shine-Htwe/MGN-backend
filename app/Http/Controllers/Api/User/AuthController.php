<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {

    }

    public function login(LoginRequest $request)
    {
        $process =  new \App\Services\Auth\Authentication($request);

        return $process->returnResponse('api')->signIn('web', '');
    }
}
