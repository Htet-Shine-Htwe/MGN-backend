<?php

namespace App\Traits;

use Closure;
use Illuminate\Support\Facades\Cache;

trait CacheResponse
{
    public bool $cacheMode = true;

    public function cacheResponse(string $key,int $minutes ,Closure $callback
     ): mixed
    {
        if (!$this->cacheMode) {
            return $callback();
        }

        $data = Cache::get($key);

        if ($data === null) {
            $process = $callback();

            if (is_array($process) || is_object($process)) {
                $process = $callback();

            }

            return Cache::remember($key, $minutes, function () use ($process) {
                return $process;
            });

        }
        return $data;
    }

    public function updateCache(string $key,mixed $data,int|null $minutes = null): void
    {
        if ($minutes) {
            Cache::put($key, $data, $minutes);
        } else {
            Cache::forever($key, $data);
        }
    }

    public function forgetCache(string $key): void
    {
        Cache::forget($key);
    }

    public function clearCache(): void
    {
        Cache::flush();
    }

    public function generateCacheKey(string $key): string
    {
        $appName = config('app.name');

        if (!is_string($appName)) {
            $appName = 'default-app';
        }

        return sprintf("%s-%s", $appName, $key);
    }

/**
     * Cache data with tags.
     *
     * @param array<string>|string $tags The cache tags
     * @param string $key The cache key
     * @param int $minutes Time in minutes to cache
     * @param Closure $callback The callback to generate cache data
     *
     * @return mixed The cached data
     */
    public function cacheTags(array|string $tags, string $key, int $minutes , Closure $callback): mixed
    {
        return Cache::tags($tags)->remember($key, $minutes, function () use ($callback) {
            return $callback();
        });
    }
   /**
     * Forget cached data by tags.
     *
     * @param array<string>|string $tags The cache tags
     * @param string $key The cache key
     */
    public function forgetCacheTags(array|string $tags, string $key): void
    {
        Cache::tags($tags)->forget($key);
    }

    /**
     * Clear cache by tags.
     *
     * @param array<string>|string $tags The cache tags
     */
    public function clearCacheTags(array|string $tags): void
    {
        Cache::tags($tags)->flush();
    }
}
