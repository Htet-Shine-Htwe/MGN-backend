<?php

use App\Http\Controllers\Api\User\HomePageController;
use App\Http\Controllers\Api\User\UserFavoriteController;
use App\Http\Controllers\Api\User\UserMogouController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Telegram\Telegram;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramUpdates;
use WeStacks\TeleBot\TeleBot;

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

Route::get('/ci-test',function(){
    return 'CI test';
});

Route::get('/telegram-test',function(){

    $bot = new TeleBot(env('TELEGRAM_BOT_TOKEN'));

    $bot->sendMessage([
        'chat_id' =>'-1002198423534',
        'text' => 'This is send by bbot'
    ]);

    return $bot->getUpdates();
});

Route::get('/env',function(){
    dd(env('APP_ENV'));
});

Route::get('/throw-slack-exception',function(){
     throw new \Exception('This is a slack exception');
});

