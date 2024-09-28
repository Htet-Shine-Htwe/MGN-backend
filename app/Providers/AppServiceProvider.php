<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->app->make('App\Http\Controllers\Controller')->add

        // DbPartitionInterface

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
