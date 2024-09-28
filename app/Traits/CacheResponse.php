<?php

namespace App\Traits;
use Illuminate\Support\Facades\Cache;

trait CacheResponse
{

    public $cacheMode = true;

    public function cacheResponse($key, $minutes=300 , $callback = null)
    {
        if (!$this->cacheMode) {
            return $callback();
        }

        $data = Cache::get($key);

        if ($data === null) {
            $process = $callback;

            return Cache::remember($key, $minutes, $process);

        }
        return $data;
    }

    public function updateCache($key, $data, $minutes = null)
    {
        if ($minutes) {
            Cache::put($key, $data, $minutes);
        } else {
            Cache::forever($key, $data);
        }
    }

    public function forgetCache($key)
    {
        Cache::forget($key);
    }

    public function clearCache()
    {
        Cache::flush();
    }

    public function generateCacheKey($key)
    {
        return sprintf("%s-%s", config('app.name'), $key);
    }

    public function cacheTags($tags, $key, $minutes = 300, $callback = null)
    {
        return Cache::tags($tags)->remember($key, $minutes, $callback);
    }

    public function forgetCacheTags($tags, $key)
    {
        Cache::tags($tags)->forget($key);
    }

    public function clearCacheTags($tags)
    {
        Cache::tags($tags)->flush();
    }
}
