<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserAvatar\UserAvatarService;
use App\Traits\CacheResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAvatarController extends Controller
{
    use CacheResponse;

    public function __construct(protected UserAvatarService $userAvatarService,private string $cacheKey = "")
    {
        $this->cacheKey = $this->generateCacheKey('user-avatars');
    }

    public function get(): JsonResponse
    {
        $key = $this->cacheKey;
        $avatars = $this->cacheResponse(
            $key, 300, function () {
                return $this->userAvatarService->getUserAvatars();
            }
        );

        return response()->json(
            [
                    'user_avatars' => $avatars
            ]
        );
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(
            [
                'avatar_name' => 'required|string',
                'avatar' => 'required|image|mimes:png|max:3072'
            ]
        );

        $avatar = $this->userAvatarService->createNewAvatar($request->avatar_name, $request->file('avatar'));

        $this->forgetCache($this->cacheKey);
        return response()->json(
            [
                'message' => 'Avatar created successfully',
                'user_avatar' => $avatar
            ]
        );
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate(
            [
                'id' => 'required|exists:user_avatars,id',
                'avatar_name' => 'required|string',
                'avatar' => 'required|image|mimes:png|max:3072'
            ]
        );

        $avatar = $this->userAvatarService->updateUserAvatar($request->id, $request->avatar_name, $request->file('avatar'));
        $this->forgetCache($this->cacheKey);

        return response()->json(
            [
                'message' => 'Avatar updated successfully',
                'user_avatar' => $avatar
            ]
        );
    }

    public function destroy(Request $request): JsonResponse
    {
        $ids = $request->ids;

        $this->userAvatarService->bulkDeleteUserAvatars($ids);
        $this->forgetCache($this->cacheKey);

        return response()->json(
            [
                'message' => 'Deleted successfully'
            ]
        );
    }


}
