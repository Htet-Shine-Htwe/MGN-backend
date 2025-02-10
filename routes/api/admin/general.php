<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\AdminReportController;
use App\Http\Controllers\Api\Admin\AnalysisReportController;
use App\Http\Controllers\Api\Admin\ApplicationConfigController;
use App\Http\Controllers\Api\Admin\BotPublisherController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\MogouChapterController;
use App\Http\Controllers\Api\Admin\MogouController;
use App\Http\Controllers\Api\Admin\SectionManagementController;
use App\Http\Controllers\Api\Admin\SocialChannelController;
use App\Http\Controllers\Api\Admin\SocialInfoController;
use App\Http\Controllers\Api\Admin\SubMogouController;
use App\Http\Controllers\Api\Admin\SubscriptionController;
use App\Http\Controllers\Api\Admin\UserAvatarController;
use App\Http\Controllers\Api\Admin\UserSubscriptionController;
use App\Http\Controllers\TestController;
use App\Services\BotPublisher\GetBotServices;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
->prefix('admin')
->name('admin.')->group(function(){
    // Change Password
    Route::get('/roles',[AdminController::class,'roles'])->name('roles.index');
    Route::post('/roles',[AdminController::class,'createRole'])->name('roles.store');

    Route::get('/permissions',[AdminController::class,'permissions'])->name('permissions.index');

    Route::get('/members',[AdminController::class,'members'])->name('members.index');

    Route::controller(DashboardController::class)->group(function(){
        Route::get('/dashboard/stats','stats')->name('dashboard.stats');
        Route::get('/dashboard/user-locations','userLocation')->name('dashboard.user.location');
        Route::get('/dashboard/user-registrations','userRegistrationByMonths')->name('dashboard.user.registration');
        Route::get('/dashboard/user-logins','userLoginThisWeek')->name('dashboard.user.login');
        Route::get('/dashboard/user-traffics','userTrafficSummary')->name('dashboard.user.traffics');
    });

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
        Route::post('/users/update-subscription','update')->name('subscription-users.update-subscription');


        Route::get('/users/{user_code}/subscriptions','subscriptions')->name('subscription-users.subscriptions');
        Route::get('/users/show/{user_code}','show')->name('subscription-users.show');
        Route::get('/users/showById/{id}','showById')->name('subscription-users.showById');
    });

    Route::controller( UserAvatarController::class)->group(function(){
        Route::get('/user-avatars','get')->name('avatars');
        Route::post('/user-avatars/create','store')->name('avatars.store');
        Route::post('/user-avatars/update','update')->name('avatars.update');
        Route::post('/user-avatars/delete','destroy')->name('avatars.delete');
    });

    Route::controller(MogouController::class)->group(function(){
        Route::get('/mogous','index')->name('mogous.index');

        Route::get("/mogous/{mogou}",'show')->name('mogous.show');
        Route::post('/mogous','create')->name('mogous.store');
        Route::post('/mogous/update-status','updateStatus')->name('mogous.updateStatus');
        Route::post('/mogous/add-category','bindCategory')->name('mogous.addCategory');
        Route::post('/mogous/remove-category','unbindCategory')->name('mogous.removeCategory');
        Route::post('/mogous/{mogou}','update')->name('mogous.update');
        Route::post('/delete/mogous','delete')->name('mogous.delete');
    });

    Route::controller(MogouChapterController::class)->group(function(){
        Route::get('/mogous/{mogou}/chapters','index')->name('mogou-chapters.index');
        Route::get("/mogous/{mogou}/analysis",'chapterAnalysis')->name('mogou-chapters.chapterAnalysis');
        Route::post('/mogous/{mogou}/chapters','create')->name('mogou-chapters.store');
        Route::post('/mogous/{mogou}/chapters/{chapter}','update')->name('mogou-chapters.update');
        Route::post('/mogous/{mogou}/chapters/{chapter}/delete','delete')->name('mogou-chapters.delete');
    });

    Route::controller(SubMogouController::class)->group(function(){
        Route::post('/sub-mogous/new-draft','saveNewDraft')->name('sub-mogous.saveNewDraft');
        Route::post('/sub-mogous/update-draft','updateInfo')->name('sub-mogous.updateInfo');
        Route::post('/sub-mogous/update-cover','updateCover')->name('sub-mogous.updateCover');
        Route::get('/sub-mogous/show/{mogou_slug}/{sub_mogou_id}','show')->name('sub-mogous.show');
        Route::get("/sub-mogous/get-latest-chapter/{mogou_slug}",'getLatestChapterNumber')->name('sub-mogous.getLatestChapterNumber');
        Route::post("/sub-mogous/upload-files",'uploadStorageFiles')->name('sub-mogous.uploadStorageFiles');
        Route::post("/sub-mogous/delete",'deleteSubMogou')->name('sub-mogous.deleteSubMogou');
    });

    Route::controller(SocialInfoController::class)->group(function(){
        Route::get('/social-info','index')->name('social-info.index');
        Route::post('/social-info','store')->name('social-info.store');
        Route::post('/social-info/update/{social_info}','update')->name('social-info.update');
        Route::post('/social-info/{social_info}','delete')->name('social-info.delete');

        Route::get('/social-info/banners','banners')->name('social-info.banners');
    });

    Route::controller(SectionManagementController::class)->group(function(){
        Route::get('/sections/{section}','index')->name('sections.index');
        Route::post('/search_section_items','searchMogou')->name('sections.searchItem');
        Route::post('/sections/{section}','attachNewChild')->name('sections.update');
        Route::post('/sections/{section}/delete','removeChild')->name('sections.delete');
        Route::post('/section_items/visibility','setVisibility')->name('sections.searchItem');
        Route::post('/sections/{section}/empty','emptySection')->name('sections.empty');
    });

    Route::controller(BotPublisherController::class)->name('bot-publisher.')->group(function(){
        Route::get("/bot-publisher/{type}/list",'index')->name('index');
        Route::get("/bot-publisher/{id}/detail",'showBot')->name('showBot');
        Route::post('/bot-publisher','store')->name('store');
        Route::post('/bot-publisher/remove','remove')->name('remove');
    });

    Route::controller(SocialChannelController::class)->group(function(){
        Route::post('/social-channel','create')->name('social-channels.store');
    });

    Route::controller(AdminReportController::class)->group(function(){
        Route::get('/reports','index')->name('reports.index');
        Route::get('/reports/{report}','show')->name('reports.show');
        Route::post('/reports/{report}','updateStatus')->name('reports.updateStatus');
    });

    Route::controller(AnalysisReportController::class)->group(function(){
        Route::get('/subscription-analysis','subscriptionAnalysis')->name('analysis-report.subscription');
    });

    Route::controller(ApplicationConfigController::class)->group(function(){
        Route::post('/application-configs','update')->name('application-configs.store');

    });
});


Route::get("/test",function(){
    $bot = (new GetBotServices())->getBot(1)->getPublisher()->self();
    $bot->sendMessage([
        'chat_id' => "1002249930897",
        'text'    => 'Hello, from Laravel\'s notifications!'
    ]);
});
