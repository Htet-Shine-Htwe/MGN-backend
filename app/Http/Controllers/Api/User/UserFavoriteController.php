<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFavoriteRequest;
use App\Models\User;
use App\Repo\User\Favorite\UserFavoriteRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserFavoriteController extends Controller
{
    public function __construct(protected UserFavoriteRepo $userFavoriteRepo)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            [
            'favorites' => $this->userFavoriteRepo->getFavorites()
            ]
        );
    }

    public function create(UserFavoriteRequest $request): JsonResponse
    {
        // make auth user as User
        $user = $request->user();

        $this->userFavoriteRepo->setUser($user);

        $added = $this->userFavoriteRepo->addFavorite($request->mogou_id);

        if ($added) {
            return response()->json(['message' => 'Favorite added']);
        }

        return response()->json(['message' => 'Already added'], 400);

    }

    public function delete(UserFavoriteRequest $request): JsonResponse
    {
        $user = $request->user();

        $this->userFavoriteRepo->setUser($user);

        $this->userFavoriteRepo->removeFavorite($request->mogou_id);

        return response()->json(['message' => 'Favorite removed']);
    }
}
