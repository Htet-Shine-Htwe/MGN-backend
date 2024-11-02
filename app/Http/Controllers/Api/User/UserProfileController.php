<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Repo\Admin\Subscription\UserSubscriptionRepo;
use App\Repo\Admin\UserRegistrationRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function __construct(
        protected UserRegistrationRepo $userRegistrationRepo,
        protected UserSubscriptionRepo $userSubscriptionRepo
    ) {
    }

    public function getProfile(Request $request): JsonResponse
    {
        $auth_user = auth()->user();
        $user = $this->userRegistrationRepo->show('user_code',$auth_user->user_code);

        $user_subscriptions = $this->userSubscriptionRepo->setUser($user->user_code)->subscriptions();

        return response()->json(
            [
            'user' => $user,
            'subscriptions' => $user_subscriptions
            ]
        );
    }

    public function updateProfile(UserRegistrationRequest $request) :JsonResponse
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
                    'message' => 'Profile updated successfully',
                    'user' => $user
                    ]
                );
            },null,true
        );
    }
}
