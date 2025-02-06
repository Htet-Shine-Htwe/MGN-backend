<?php

use App\Http\Middleware\UserMaintenanceModeMiddleware;
use App\Models\ApplicationConfig;

use Illuminate\Support\Facades\Route;

uses()->group('unit','middleware');

beforeEach(function () {
    Route::middleware(UserMaintenanceModeMiddleware::class)->get('/test-route', function () {
        return response()->json(['message' => 'Success'], 200);
    });
});

it('blocks access when user side is in maintenance mode', function () {
    ApplicationConfig::factory()->create(['user_side_is_maintenance_mode' => true]);

    $response = $this->get('/test-route');

    $response->assertStatus(503);
    $response->assertJson(['message' => 'The application is in maintenance mode. Please try again later.']);
});


it('allows access when user side is not in maintenance mode', function () {
    ApplicationConfig::factory()->create(['user_side_is_maintenance_mode' => false]);

    $response = $this->get('/test-route');

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Success']);
});

