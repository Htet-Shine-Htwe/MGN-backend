<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Storage::extend('bunnycdn', function ($app, $config) {
        //     $adapter = new BunnyCDNAdapter(
        //         new BunnyCDNClient(
        //             $config['storage_zone'],
        //             $config['api_key'],
        //             $config['region']
        //         ),
        //         $config['pull_zone']
        //     );

        //     return new FilesystemAdapter(
        //         new Filesystem($adapter, $config),
        //         $adapter,
        //         $config
        //     );
        // });

        $this->app->singleton(
            'MangaTestClient',
            function () {

                $client = new \GuzzleHttp\Client(
                    [
                        'base_uri' => 'https://'.config('global.rapid_api.myanimelist.host') .'/',
                        'http_errors' => false,
                        'headers' => [
                            'accept' => 'application/json',
                            'x-rapidapi-host' => config('global.rapid_api.myanimelist.host'),
                            'x-rapidapi-key' => config('global.rapid_api.myanimelist.key'),
                        ]
                    ]
                );

                return $client;
            }
        );
    }
}
