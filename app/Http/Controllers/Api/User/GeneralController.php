<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\General\UserGeneralService;
use App\Traits\CacheResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    use CacheResponse;

    public function __construct(protected UserGeneralService $userGeneralService,private string $cacheKey = ""){
        $this->cacheKey = $this->generateCacheKey('user-avatars');
    }

    public function contactUs() : JsonResponse
    {
        $data = $this->cacheResponse($this->cacheKey,300,function(){
            return $this->userGeneralService->contactUsSocialLink();
        });

        return response()->json([
            'data' => $data
        ]);
    }
}
