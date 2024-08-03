<?php

use App\Http\Controllers\Api\Admin\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('admin/login',[AuthController::class,'login'])->name('admin.login')->middleware('guest:admin');

Route::middleware(['auth:sanctum'])->group(function(){
    // Change Password
    Route::post('/admin/change-password',[AuthController::class,'changePassword'])->name('admin.change-password');
});
