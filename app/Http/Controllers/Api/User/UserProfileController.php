<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
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
}
