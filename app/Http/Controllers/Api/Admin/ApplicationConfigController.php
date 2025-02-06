<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationConfig;
use App\Repo\Admin\ApplicationConfig\ApplicationConfigUploadRepo;
use App\Traits\CacheResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicationConfigController extends Controller
{
    use CacheResponse;

    private string $cacheKey;

    public function __construct(protected ApplicationConfigUploadRepo $applicationConfigUploadRepo)
    {
        $this->cacheKey = $this->generateCacheKey('application_config');
    }

    public function index(): JsonResponse
    {
        $key = $this->cacheKey;

        $app = $this->cacheResponse(
            $key, 300, function () {
                return ApplicationConfig::first();
            }
        );

        return response()->json($app);
    }

    public function update(Request $request): JsonResponse
    {
        $app = $this->applicationConfigUploadRepo->upload($request);

        $key = $this->cacheKey;
        $this->forgetCache( $key);

        return response()->json($app);
    }
}
