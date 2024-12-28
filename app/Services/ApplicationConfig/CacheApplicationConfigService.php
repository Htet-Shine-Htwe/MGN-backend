<?php

namespace App\Services\ApplicationConfig;

use App\Models\ApplicationConfig;
use App\Traits\CacheResponse;

class CacheApplicationConfigService
{
    use CacheResponse;

    private static string $cacheKey;

    public function __construct()
    {
        self::$cacheKey = $this->generateCacheKey('application-config');
    }

    public static function getApplicationConfig(): mixed
    {
        $key = self::$cacheKey ;
        $applicationConfig = (new self)->cacheResponse(
            $key, 300, function () {
                return ApplicationConfig::first();
            }
        );

        return $applicationConfig;
    }

    public function getCacheKey(){
        return $this->cacheKey;
    }
}
