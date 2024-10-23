<?php

use App\Http\Controllers\Api\User\AuthController;
use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;
use hisorange\BrowserDetect\Parser as Browser;


Route::post('users/login',[AuthController::class,'login'])->name('user.login')->middleware('guest');

// Route::middleware(['auth:sanctum'])->group(function(){
//     // Change Password
//     Route::post('/admin/change-password',[AuthController::class,'changePassword'])->name('admin.change-password');
//     Route::post('/admin/logout',[AuthController::class,'logout'])->name('admin.logout');
// });



Route::get('/request', function () {

   dd( Browser::platformName(). " ( " . Browser::browserFamily() . " ) ");
});
