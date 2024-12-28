<?php

namespace App\Providers;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use PlatformCommunity\Flysystem\BunnyCDN\BunnyCDNAdapter;
use PlatformCommunity\Flysystem\BunnyCDN\BunnyCDNClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::extend('bunnycdn', function ($app, $config) {
            $adapter = new BunnyCDNAdapter(
                new BunnyCDNClient(
                    $config['storage_zone'],
                    $config['api_key'],
                    $config['region']
                ),
                $config['pull_zone']
            );

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });


        $this->app->singleton(
            'MangaTestClient', function () {
                $client = new \GuzzleHttp\Client(
                    [
                    'base_uri' => 'https://myanimelist.p.rapidapi.com/',
                    'http_errors' => false,
                    'headers' => [
                    'accept' => 'application/json',
                    'x-rapidapi-host' => 'myanimelist.p.rapidapi.com',
                    'x-rapidapi-key' => 'bdb606d03dmshf9031f66fc37266p1aa047jsnc218d1a5aa84',
                    ]
                    ]
                );

                return $client;
            }
        );

    }
}
