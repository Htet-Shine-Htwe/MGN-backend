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

}
