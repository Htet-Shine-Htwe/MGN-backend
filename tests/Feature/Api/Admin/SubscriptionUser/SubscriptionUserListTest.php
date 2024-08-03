<?php

use App\Models\User;
use App\Models\UserSubscription;
use Database\Seeders\SubscriptionSeeder;
use Database\Seeders\UserSeeder;
use Tests\Support\UserAuthenticated;


uses()->group('admin','api','admin-subscription-list','users-subscription');
uses(UserAuthenticated::class);

beforeEach(function(){
    config(['control.test.users_count' => 30]);
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
        'subscription_name'
    ];

    // $this->subscriptions = $this->authenticatedAdmin()->getJson(route('api.admin.subscriptions.index'));
});

test("user subscriptions can be fetched",function(){
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.subscription-users.index'));

    $response->assertJsonCount(10,'users.data');

    $response->assertJsonStructure([
        'users' => [
            'data' => [
                '*' => $this->sampleUserSubscriptionJsonStructure
            ]
        ]
    ]);

    $response->assertOk();
});

test("user subscription can be query with search",function(){
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.subscription-users.index',[
        'search' => 'user12@example.com'
    ]));
    $response->assertJsonCount(1,'users.data');
    $response->assertJsonStructure([
        'users' => [
            'data' => [
                '*' => $this->sampleUserSubscriptionJsonStructure
            ]
        ]
    ]);

    $response->assertOk();
});

test("user subscription can be filter with specific current_subscription_id",function(){
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.subscription-users.index',[
        'filter' => 2
    ]));

    // every user has subscription_id 1
    $data  = $response->json('users.data');

    foreach($data as $user){
        $this->assertEquals(2,$user['current_subscription_id']);
    }
    $response->assertOk();
});

test("return empty data when no user subscription found",function(){
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.subscription-users.index',[
        'search' => 'asdffdsssan'
    ]));

    $response->assertJsonCount(0,'users.data');

    $response->assertOk();

});

test("can filter subscription users who are expired theirs subscription",function(){
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.subscription-users.index',[
        'expired' => true
    ]));

    $data = $response->json('users.data');

    foreach($data as $user){
        $this->assertTrue($user['subscription_end_date'] < now());
    }

    $response->assertOk();
});

test("collection of subscription of user can be fetched",function(){

    $user = User::first();

    UserSubscription::factory()->count(5)->create([
        'user_id' => $user->id
    ]);

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.subscription-users.subscriptions',[
        'user_code' => $user->user_code
    ]));

    $response->assertOk();

    $response->assertJsonCount(5,'subscriptions');

});

test("can successfully view the single user with his subscription history",function(){
    $user = User::first();

    UserSubscription::factory()->count(5)->create([
        'user_id' => $user->id
    ]);

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.subscription-users.show',[
        'user_code' => $user->user_code
    ]));

    $response->assertOk();

    $response->assertJsonStructure([
        'user' => [
            'user_code',
            'name',
            'email',
            'current_subscription_id',
            'subscription_name'
        ],
        'subscriptions'
    ]);

    $response->assertJsonCount(5,'subscriptions');
})->group('single-user');
