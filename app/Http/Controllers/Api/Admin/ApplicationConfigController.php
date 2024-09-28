<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationConfig;
use App\Traits\CacheResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicationConfigController extends Controller
{
    use CacheResponse;

    public function index()
    {

        $key = $this->generateCacheKey('application_config');
        $app = $this->cacheResponse(
            $key, 300, function () {
                return ApplicationConfig::first();
            }
        );

        return response()->json($app);
    }

    public function update(Request $request)
    {
        $app = ApplicationConfig::first();
        $app->update($request->all());

        $this->updateCache('Laravel-application_config', $app);

        return response()->json($app);
    }
}
