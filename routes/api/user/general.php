<?php

use App\Http\Controllers\Api\Admin\UserAvatarController;
use App\Http\Controllers\Api\User\FilterPageController;
use App\Http\Controllers\Api\User\HomePageController;
use App\Http\Controllers\Api\User\UserFavoriteController;
use App\Http\Controllers\Api\User\UserMogouController;
use App\Http\Controllers\Api\User\UserProfileController;
use App\Http\Controllers\Api\User\UserReportController;
use App\Http\Controllers\ApiUserFilterPageController;
use App\Models\Admin;
use App\Models\ChapterAnalysis;
use App\Models\SubMogouImage;
use App\Repo\Admin\SubMogouRepo\MogouPartitionFind;
use App\Services\LexoRank\LexoRankHelperService;
use Illuminate\Support\Facades\Route;

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
            Route::get("/mogous/{mogou}/chapters/{chapter}", "getChapter")->name("mogous.getChapter");
            Route::get("/mogous/{mogou}/chapters/{chapter}/viewed", "getViewed")->name("mogous.getViewed");
            Route::get('/mogous/{mogou}/related', 'relatedPostPerMogou')->name('mogous.relateMogou');
        });

        Route::controller(FilterPageController::class)->group(function(){
            Route::get('/filter','index')->name('filter.index');
        });

        Route::controller(UserReportController::class)->group(function () {
            Route::post('/create-report', 'create')->name("reports.create");
        });


        Route::get("/check-server", function () {
            return response()->json([
                'message' => 'service available',
                'status' => 200
            ], 200);
        });

        Route::get("/tester", function () {
            $collection = (new SubMogouImage)->setTable("alpha_sub_mogou_images");


            $model = MogouPartitionFind::getSubMogouImage('id',48);

            LexoRankHelperService::resetLexoRanks($model,228);
            // $new = $collection->create([
            //     'mogou_id' => 1,
            //     'sub_mogou_id' => 1,
            //     'path' => '73',
            // ]);


            // dd($collection->latest('id')->where([
            //     'mogou_id' => 1,
            //     'sub_mogou_id' => 1
            // ])->get());

            // $source = (clone $collection)->where('path','73')->first();
            // $target = (clone $collection)->where('path','55')->first();

            // $source->moveAfter($target);
            // dd($collection);
        });

    });
});
