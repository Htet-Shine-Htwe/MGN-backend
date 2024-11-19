<?php

use App\Http\Controllers\Api\Admin\UserAvatarController;
use App\Http\Controllers\Api\User\HomePageController;
use App\Http\Controllers\Api\User\UserFavoriteController;
use App\Http\Controllers\Api\User\UserMogouController;
use App\Http\Controllers\Api\User\UserProfileController;
use App\Http\Controllers\Api\User\UserReportController;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

Route::middleware(['user.maintenance'])->group(function () {

    Route::middleware(['auth:sanctum'])->prefix('users')->name('users.')->group(function () {

        Route::controller(UserFavoriteController::class)->group(function () {
            Route::get('/user-favorites', 'index')->name('user-favorites.index');
            Route::post('/user-favorites/add', 'create')->name('user-favorites.store');
            Route::post('/user-favorites/remove', 'delete')->name('user-favorites.delete');
        });

        Route::controller(UserProfileController::class)->group(function () {
            Route::get('/profile', 'getProfile')->name('profile');

            Route::post("/update/profile", "updateProfile")->name("update.profile");
        });

        Route::controller(UserAvatarController::class)->group(function () {
            Route::get('/user-avatars', 'get')->name('avatars');
        });

    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::controller(HomePageController::class)->group(function () {
            Route::get('/carousel', 'carousel')->name('carousel');
            Route::get('/carousel/most-viewed', 'mostViewed')->name('most-viewed');
            Route::get('/carousel/recommended', 'recommended')->name('last-uploaded');
            Route::get('/last-uploaded', 'lastUploaded')->name('last-uploaded');
            Route::get('/banners', 'banners')->name('banners');
        });

        Route::controller(UserMogouController::class)->group(function () {
            Route::get('/mogous/{mogou}', 'show')->name('mogous.show');
            Route::get('/mogous/{mogou}/getMoreChapters', 'getMoreChapters')->name('mogous.getMoreChapters');
            Route::get('/mogous/{mogou}/related', 'relatedPostPerMogou')->name('mogous.relateMogou');
        });

        Route::controller(UserReportController::class)->group(function () {
            Route::post('/create-report', 'create')->name("reports.create");
        });


        Route::get("/check-server",function(){
            return response()->json([
                'message' => 'service available',
                'status' => 200
            ], 200);
        });


        Route::get("/test", function() {
            $client = new Client();
            $response = $client->get('https://radian-mgn.b-cdn.net/public/config/bg_02_673724cd5ef88.jpg');

            // Get the image content as a string
            $imageContent = $response->getBody()->getContents();

            // Base64 encode the image to embed it directly in the img tag
            $base64Image = base64_encode($imageContent);

            // Return an image using the <img> tag
            return "<img src='data:image/jpeg;base64,{$base64Image}' alt='Image from URL' />";
        });


    });
});
