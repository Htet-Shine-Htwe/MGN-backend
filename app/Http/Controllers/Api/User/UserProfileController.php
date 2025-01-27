<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\Admin;
use App\Repo\Admin\Subscription\UserSubscriptionRepo;
use App\Repo\Admin\UserRegistrationRepo;
use App\Repo\User\Favorite\UserFavoriteRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function __construct(
        protected UserRegistrationRepo $userRegistrationRepo,
        protected UserSubscriptionRepo $userSubscriptionRepo,
        protected UserFavoriteRepo $userFavoriteRepo
    ) {
    }

    public function getProfile(Request $request): JsonResponse
    {
        $auth_user = auth()->user();

        if($auth_user instanceof Admin){
            // return unauthorized
            return response()->json(
                [
                'message' => 'User not found ! Please login again'
                ],401
            );
        }

        $user = $this->userRegistrationRepo->show('user_code',$auth_user->user_code);

        $user_subscriptions = $this->userSubscriptionRepo->setUser($user->user_code)->subscriptions();
        $user_favorites = $this->userFavoriteRepo->setUser($user)->getFavoriteMogous();

        return response()->json(
            [
            'user' => $user,
            'subscriptions' => $user_subscriptions,
            'favorites' => $user_favorites
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
