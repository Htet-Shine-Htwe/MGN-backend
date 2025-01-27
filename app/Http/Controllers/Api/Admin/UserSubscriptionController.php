<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use App\Repo\Admin\UserRegistrationRepo;
use App\Repo\Admin\Subscription\UserSubscriptionRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSubscriptionController extends Controller
{
    public function __construct(
        protected UserRegistrationRepo $userRegistrationRepo,
        protected UserSubscriptionRepo $userSubscriptionRepo
    ) {
    }

    public function index(Request $request) :JsonResponse
    {
        $users = $this->userRegistrationRepo->list($request);

        return response()->json(
            [
            'users' => $users
            ]
        );
    }

    public function create(UserRegistrationRequest $request) :JsonResponse
    {
        $request->validate([
            'user_code' => "unique:users,user_code",
            'password' => 'required|string|min:8'
        ]);

        return tryCatch(
            function () use ($request) {
                $user =  $this->userRegistrationRepo->registerUser($request);
                return response()->json(
                    [
                    'message' => 'User registered successfully',
                    'user' => $user
                    ]
                );
            },null,true
        );
    }

    public function update(UserRegistrationRequest $request) :JsonResponse
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'user_code' => "unique:users,user_code,".$request->input('id'),
        ]);

        $id = $request->input('id');
        return tryCatch(
            function () use ($request,$id) {
                $user = $this->userRegistrationRepo->updateUser($request, $id);
                return response()->json(
                    [
                    'message' => 'User updated successfully',
                    'user' => $user
                    ]
                );
            },null,true
        );
    }

    public function show(Request $request) :JsonResponse
    {
        $user = $this->userRegistrationRepo->show('user_code',$request->user_code);

        $user_subscriptions = $this->userSubscriptionRepo->setUser($user->user_code)->subscriptions();

        return response()->json(
            [
            'user' => $user,
            'subscriptions' => $user_subscriptions
            ]
        );
    }

    public function showById(Request $request) :JsonResponse
    {
        $user = $this->userRegistrationRepo->show('id',$request->id);

        $user_subscriptions = $this->userSubscriptionRepo->setUser($user->user_code)->subscriptions();
        $user_login_history = $this->userSubscriptionRepo->setUser($user->user_code)->login_history();

        return response()->json(
            [
            'user' => $user,
            'subscriptions' => $user_subscriptions,
            'login_history' => $user_login_history
            ]
        );
    }

    public function subscriptions(string $user_code) :JsonResponse
    {
        $user_subscriptions = $this->userSubscriptionRepo->setUser($user_code)->subscriptions();

        return response()->json(
            [
            'subscriptions' => $user_subscriptions
            ]
        );
    }
}
