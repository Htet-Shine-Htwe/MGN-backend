<?php

use App\Models\User;
use Database\Seeders\SubscriptionSeeder;
use Illuminate\Support\Facades\Route;

use Tests\Support\UserAuthenticated;

uses()->group('user','api','user-auth');
uses(UserAuthenticated::class);


beforeEach(function(){
    $this->seed([
        SubscriptionSeeder::class
    ]);
});

test('user login route exists', function () {
    $this->assertTrue(Route::has('api.admin.login'));
});


test("user can login successfully",function(){
    $user = User::factory()->create();

    $response = $this->postJson(route("api.user.login"),[
        'user_code' => $user->user_code,
        'password' => 'password'
    ]);

    $response->assertStatus(200)->assertJsonStructure([
        'token',
        'user'
    ]);
});

test("User Login Request Validation",function(){

    $response = $this->postJson(route("api.user.login"),[]);

    $response->assertStatus(422)->assertJsonStructure([
        'message',
        'errors'
    ]);
});
