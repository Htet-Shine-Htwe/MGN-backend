<?php

use App\Enum\SocialInfoType;
use Illuminate\Http\UploadedFile;

use Tests\Support\TestStorage;
use Tests\Support\UserAuthenticated;


uses()->group('admin','api','socialinfo');
uses(UserAuthenticated::class,TestStorage::class);

beforeEach(function(){
    $this->setupAdmin();

    $this->bootStorage();

    $this->dummyData = [
        'name' => 'Facebook',
        'type' => SocialInfoType::ReferSocial,
        'icon' => 'facebook',
        'cover_photo' => UploadedFile::fake()->image('cover.jpg'),
        'redirect_url' => 'https://facebook.com'
    ];
});


it('has admin/socialinfo/socialinfo page', function () {
    $response = $this->getJson(route('api.admin.social-info.index'));

    $response->assertStatus(200);
});

it('new social info can create successfully !', function () {
    $response = $this->postJson(route('api.admin.social-info.store'), $this->dummyData);

    $response->assertStatus(201);
});

it("validation for creating new social info",function(){
    $this->dummyData['redirect_url'] = '';
    $response = $this->postJson(route('api.admin.social-info.store'), $this->dummyData);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('redirect_url');
});

it('social info can update successfully !', function () {

    $socialInfo = $this->postJson(route('api.admin.social-info.store'),$this->dummyData)->json();

    $response = $this->postJson(route('api.admin.social-info.update',$socialInfo['social_info']['id']), [
        'name' => 'Facebook',
        'type' => SocialInfoType::ReferSocial,
        'icon' => 'facebook',
        'cover_photo' => UploadedFile::fake()->image('cover.jpg'),
        'redirect_url' => 'https://facebook.com'
    ]);

    $response->assertStatus(200);
});


it("can't delete social info with invalid id",function(){
    $response = $this->postJson(route('api.admin.social-info.delete',33));

    $response->assertStatus(404);
});

it("social info can delete successfully !",function(){
    $socialInfo = $this->postJson(route('api.admin.social-info.store'),$this->dummyData)->json();

    $response = $this->postJson(route('api.admin.social-info.delete',$socialInfo['social_info']['id']));

    $response->assertStatus(200);
});

it("can create social info start banner",function(){

    $response = $this->postJson(route('api.admin.social-info.store'),[
        'name' => 'Facebook',
        'type' => SocialInfoType::Banner,
        'icon' => 'facebook',
        'cover_photo' => UploadedFile::fake()->image('cover.jpg'),
        'redirect_url' => 'https://facebook.com'
    ]);

    $response->assertStatus(201);
})->group('test');




