<?php

use App\Http\Controllers\Api\User\HomePageController;
use App\Http\Controllers\Api\User\UserFavoriteController;
use App\Http\Controllers\Api\User\UserMogouController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->name('users.')->group(function(){

    Route::controller(UserFavoriteController::class)->group(function(){
        Route::get('/user-favorites','index')->name('user-favorites.index');
        Route::post('/user-favorites/add','create')->name('user-favorites.store');
        Route::post('/user-favorites/remove','delete')->name('user-favorites.delete');
    });

});

Route::prefix('users')->name('users.')->group(function(){
    Route::controller(HomePageController::class)->group(function(){
        Route::get('/carousel','carousel')->name('carousel');
        Route::get('/most-viewed','mostViewed')->name('most-viewed');
        Route::get('/last-uploaded','lastUploaded')->name('last-uploaded');
    });

    Route::controller(UserMogouController::class)->group(function(){

        Route::get('/mogous/{mogou}','show')->name('mogous.show');
        Route::get('/mogous/{mogou}/related','relatedPostPerMogou')->name('mogous.relateMogou');

    });

});


Route::get('/throw-slack-exception',function(){
     throw new \Exception('This is a slack exception');

});
