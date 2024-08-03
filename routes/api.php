<?php

use App\Http\Controllers\Api\Admin\ApplicationConfigController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Models\Mogou;
use App\Services\Api\DataClient;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::prefix('v1')
    ->name('api.')
    ->group(function ()
    {
        \App\Services\Route\RouteHelper::includedRouteFiles(__DIR__ . '/api');
        Route::controller(ApplicationConfigController::class)->group(function(){
            Route::get('/application-configs','index')->name('application-configs.index');
            Route::post('/application-configs','update')->name('application-configs.store');
        });

        Route::get("/public/categories",[CategoryController::class,"all"]);
    });

