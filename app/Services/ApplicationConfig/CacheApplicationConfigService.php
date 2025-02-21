<?php

namespace App\Services\ApplicationConfig;

use App\Models\ApplicationConfig;
use App\Traits\CacheResponse;

class CacheApplicationConfigService
{
    use CacheResponse;

    private string $cacheKey;

    public function __construct()
    {
        $this->cacheKey = $this->generateCacheKey('application_config');

    }

    public function getApplicationConfig(): mixed
    {
        $key = $this->cacheKey ;
        $applicationConfig = (new self)->cacheResponse(
            $key, 300, function () {
                return ApplicationConfig::first();
            }
        );

        return $applicationConfig;
    }

}
