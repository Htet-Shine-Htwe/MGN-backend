<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\ApplicationConfigController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\MogouController;
use App\Http\Controllers\Api\Admin\SocialInfoController;
use App\Http\Controllers\Api\Admin\SubMogouController;
use App\Http\Controllers\Api\Admin\SubscriptionController;
use App\Http\Controllers\Api\Admin\UserSubscriptionController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
->prefix('admin')
->name('admin.')->group(function(){
    // Change Password
    Route::get('/roles',[AdminController::class,'roles'])->name('roles.index');
    Route::post('/roles',[AdminController::class,'createRole'])->name('roles.store');

    Route::get('/permissions',[AdminController::class,'permissions'])->name('permissions.index');

    Route::get('/members',[AdminController::class,'members'])->name('members.index');

    Route::controller(SubscriptionController::class)->group(function(){
        Route::get('/subscriptions','index')->name('subscriptions.index');
        Route::get('/subscriptions/{subscription}','show')->name('subscriptions.show');
        Route::post('/subscriptions','create')->name('subscriptions.store');
        Route::put('/subscriptions/{subscription}','update')->name('subscriptions.update');
        Route::post('/subscriptions/{subscription}','delete')->name('subscriptions.delete');
    });

    Route::controller(CategoryController::class)->group(function(){
        Route::get('/categories','index')->name('categories.index');
        Route::post('/categories','create')->name('categories.store');
        Route::put('/categories/{category}','update')->name('categories.update');
        Route::post('/categories/{category}','delete')->name('categories.delete');
    });

    Route::controller(UserSubscriptionController::class)->group(function(){
        Route::get('/users','index')->name('subscription-users.index');
        Route::post('/users','create')->name('subscription-users.store');
        Route::post('/users/update','update')->name('subscription-users.update');

        Route::get('/users/{user_code}/subscriptions','subscriptions')->name('subscription-users.subscriptions');
        Route::get('/users/show/{user_code}','show')->name('subscription-users.show');
    });

    Route::controller(MogouController::class)->group(function(){
        Route::get('/mogous','index')->name('mogous.index');

        Route::get("/mogous/{mogou}",'show')->name('mogous.show');
        Route::post('/mogous','create')->name('mogous.store');
        Route::post('/mogous/update-status','updateStatus')->name('mogous.updateStatus');
        Route::post('/mogous/add-category','bindCategory')->name('mogous.addCategory');
        Route::post('/mogous/remove-category','unbindCategory')->name('mogous.removeCategory');
        Route::put('/mogous/{mogou}','update')->name('mogous.update');
        Route::post('/delete/mogous','delete')->name('mogous.delete');
    });

    Route::controller(SubMogouController::class)->group(function(){
        Route::post('/sub-mogous/new-draft','saveNewDraft')->name('sub-mogous.saveNewDraft');
        Route::post('/sub-mogous/update-cover','updateCover')->name('sub-mogous.updateCover');
        Route::get('/sub-mogous/{mogous_id}/{sub_mogou_id}','show')->name('sub-mogous.show');
    });

    Route::controller(SocialInfoController::class)->group(function(){
        Route::get('/social-info','index')->name('social-info.index');
        Route::post('/social-info','store')->name('social-info.store');
        Route::put('/social-info/{social_info}','update')->name('social-info.update');
        Route::post('/social-info/{social_info}','delete')->name('social-info.delete');

        Route::get('/social-info/banners','banners')->name('social-info.banners');
    });
});



Route::controller(TestController::class)->group(function(){
    Route::post('/test','test')->name('test');
});
