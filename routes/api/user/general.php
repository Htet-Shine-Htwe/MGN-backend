<?php

use App\Http\Controllers\Api\User\HomePageController;
use App\Http\Controllers\Api\User\UserFavoriteController;
use App\Http\Controllers\Api\User\UserMogouController;
use App\Models\BotPublisher;
use App\Services\BotPublisher\Bots\TelegramBotPublisher;
use App\Services\BotPublisher\Publisher\SocialPublisher;
use Illuminate\Http\Request;
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
        Route::get('/banners','banners')->name('banners');
    });

    Route::controller(UserMogouController::class)->group(function(){

        Route::get('/mogous/{mogou}','show')->name('mogous.show');
        Route::get('/mogous/{mogou}/related','relatedPostPerMogou')->name('mogous.relateMogou');

    });

});

Route::get('/ci-test',function(){
    return 'CI test';
});

Route::get('/telegram-send',function(Request $request){
    $publisher = BotPublisher::first();
    $bot = new TeleBot($publisher->token_key);

    $bot->sendMessage([
        'chat_id' =>'-1002198423534',
        'text' => $request->input('message') ?? 'Hello World'
    ]);

    return $bot->getUpdates();
});

Route::get('/telegram-get',function(Request $request){
    $publisher = BotPublisher::first();
    $bot =  (new SocialPublisher($publisher));


    return response()->json([
       'bot' => $bot->getInfo()
    ]);
});

Route::get('/env',function(){
    dd(env('APP_ENV'));
});

Route::get('/throw-slack-exception',function(){
     throw new \Exception('This is a slack exception');
});

