<?php

use App\Models\User;
use Database\Seeders\SubscriptionSeeder;
use Database\Seeders\UserSeeder;
use Tests\Support\UserAuthenticated;


uses()->group('admin','api','admin-subscription-action','users-subscription');
uses(UserAuthenticated::class);

beforeEach(function(){
    config(['control.test.users_count' => 10]);
    $this->seed([
        SubscriptionSeeder::class,
        UserSeeder::class
    ]);
    $this->setupAdmin();

    $this->sampleUserSubscriptionJsonStructure = [
        'id',
        'name',
        'email',
        'current_subscription_id',
        'subscription_name',
        'user_code',
    ];

});

dataset('sample_user',[
    fn() => [
        'name' => 'alpha',
        'email' => 'alpha@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'current_subscription_id' => 1,
    ],
    fn() =>[
        'name' => 'beta',
        'email' => 'beta@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'current_subscription_id' => 2,
    ]
]);

test("request body validation for subscription user",function(){
    $response = $this->authenticatedAdmin()->postJson(route('api.admin.subscription-users.store'),[]);

    $response->assertJsonStructure([
        'message',
        'errors' => [
            'name',
            'email',
        ]
    ]);
});

test("can't register duplicate email ",function($data){
    User::factory()->create([
        'email' => $data['email']
    ]);

    $response = $this->authenticatedAdmin()->postJson(route('api.admin.subscription-users.store'),$data);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => [
                'email'
            ]
        ]);
})
->with('sample_user');

test("subscription user can registered successfully",function($data){
    $response = $this->authenticatedAdmin()->postJson(route('api.admin.subscription-users.store'),$data);

    $response->assertOk()
        ->assertJson([
            'message' => 'User registered successfully'
        ]);

    $this->assertDatabaseHas('users',[
        'email' => $data['email']
    ]);
})
->with('sample_user');

test("subscription user can be updated",function($data){
    $user = User::factory()->create();

    $data['user_code'] = $user->user_code;

    $response = $this->authenticatedAdmin()->postJson(route('api.admin.subscription-users.update'),$data);


    $response->assertOk()
        ->assertJson([
            'message' => 'User updated successfully'
        ]);

    $this->assertDatabaseHas('users',[
        'email' => $data['email']
    ]);
})
->with('sample_user');

test("subscription user can't update with same email",function($data){
    $user = User::factory()->create();
    User::factory()->create([
        'email' => $data['email']
    ]);

    $data['user_code'] = $user->user_code;


    $response = $this->authenticatedAdmin()->postJson(route('api.admin.subscription-users.update'),$data);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => [
                'email'
            ]
        ]);
})
->with('sample_user');
