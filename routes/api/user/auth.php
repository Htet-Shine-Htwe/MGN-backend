<?php

use App\Http\Controllers\Api\User\AuthController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;

// Route::post('users/login',[AuthController::class,'login'])->name('admin.login')->middleware('guest:admin');



// Route::middleware(['auth:sanctum'])->group(function(){
//     // Change Password
//     Route::post('/admin/change-password',[AuthController::class,'changePassword'])->name('admin.change-password');
//     Route::post('/admin/logout',[AuthController::class,'logout'])->name('admin.logout');
// });


Route::get('/request', function () {
    // IP from request
    // will be false locally, as it is
    // the same as 127.0.0.1
    return Location::get(Request::ip());
});