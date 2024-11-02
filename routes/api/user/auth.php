<?php

use App\Http\Controllers\Api\User\AuthController;
use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;
use hisorange\BrowserDetect\Parser as Browser;


Route::post('users/login',[AuthController::class,'login'])->name('user.login')->middleware('guest');

Route::post("users/logout",[AuthController::class,'logout'])->name('user.logout')->middleware('auth:sanctum');

Route::get('/request', function () {

   dd( Browser::platformName(). " ( " . Browser::browserFamily() . " ) ");
});
