<?php

use App\Models\UserAvatar;
use Illuminate\Http\UploadedFile;
use Tests\Support\TestStorage;

uses()->group('service','user_avatar');
uses(TestStorage::class);

beforeEach(function () {
    $this->bootStorage();

    $this->userAvatarService = new \App\Services\UserAvatar\UserAvatarService();
});

it("should retrievable all avatar from services",function(){
    UserAvatar::factory()->count(3)->create();

    $this->mock(UserAvatar::class,function($userAvatar){
        $userAvatar->shouldReceive('all')->andReturn(UserAvatar::all());
    });

    $avatars = $this->userAvatarService->getUserAvatars();

    expect($avatars)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});

it("can get single avatar",function(){
    UserAvatar::factory()->count(1)->create();

    $this->mock(UserAvatar::class,function($userAvatar){
        $userAvatar->shouldReceive('find')->andReturn(UserAvatar::first());
    });

    $avatar = $this->userAvatarService->getUserAvatarById(1);
    expect($avatar)->toBeInstanceOf(UserAvatar::class);
});

it("can create new avatar successfully",function(){

    $this->mock(\HydraStorage\HydraStorage\Service\Option\MediaOption::class,function($mediaOption){
        $mediaOption->shouldReceive('setQuality')->andReturnSelf();
        $mediaOption->shouldReceive('get')->andReturn([]);
    });

    $this->mock(\HydraStorage\HydraStorage\Traits\HydraMedia::class,function($hydraMedia){
        $hydraMedia->shouldReceive('storeMedia')->andReturn('user_avatar/1.png');
    });

    $test_upload = UploadedFile::fake()->image('avatar-1.png');
    $avatar = $this->userAvatarService->createNewAvatar('avatar-1',$test_upload);
    expect($avatar)->toBeInstanceOf(UserAvatar::class);
});

it("can update existed avatar successfully",function(){

        UserAvatar::factory()->count(1)->create();

        $this->mock(\HydraStorage\HydraStorage\Service\Option\MediaOption::class,function($mediaOption){
            $mediaOption->shouldReceive('setQuality')->andReturnSelf();
            $mediaOption->shouldReceive('get')->andReturn([]);
        });

        $this->mock(\HydraStorage\HydraStorage\Traits\HydraMedia::class,function($hydraMedia){
            $hydraMedia->shouldReceive('storeMedia')->andReturn('user_avatar/1.png');
        });

        $test_upload = UploadedFile::fake()->image('avatar-1.png');
        $avatar = $this->userAvatarService->updateUserAvatar(1,'avatar-1',$test_upload);
        expect($avatar)->toBeInstanceOf(UserAvatar::class);
});

it("can remove user avatar successfully",function(){
    UserAvatar::factory()->count(1)->create();

    $this->mock(\HydraStorage\HydraStorage\Traits\HydraMedia::class,function($hydraMedia){
        $hydraMedia->shouldReceive('removeMedia')->andReturn(true);
    });

    $avatar = $this->userAvatarService->deleteUserAvatar(1);
    expect($avatar)->toBeTrue();
});

it("can remove multiple user avatars successfully",function(){
    UserAvatar::factory()->count(3)->create();

    $this->mock(\HydraStorage\HydraStorage\Traits\HydraMedia::class,function($hydraMedia){
        $hydraMedia->shouldReceive('removeMedia')->andReturn(true);
    });

    $avatar = $this->userAvatarService->bulkDeleteUserAvatars([1,2,3]);
    expect($avatar)->toBeTrue();
});
