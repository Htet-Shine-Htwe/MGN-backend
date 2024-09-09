<?php

use App\Models\User;
use Database\Seeders\SubscriptionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Schema;
use Tests\Support\UserAuthenticated;


uses()->group('admin','api','admin-subscription');
uses(UserAuthenticated::class);

beforeEach(function(){
    config(['control.test.users_count' => 30]);
    $this->seed([
        SubscriptionSeeder::class,
        UserSeeder::class
    ]);
    $this->setupAdmin();

    $this->subscriptions = $this->authenticatedAdmin()->getJson(route('api.admin.subscriptions.index'));
});

test("subscription table exists",function(){
    $this->assertTrue(Schema::hasTable('subscriptions'));
});


test("config was successfully updated",function(){

    $this->subscriptions->assertOk();

    $user_total = User::count();

    $this->assertEquals(31,$user_total);
});


test("check subscription have expected count 10",function(){

    $this->subscriptions->assertOk();

    $this->subscriptions->assertJsonCount(10,'subscriptions.data');
});

test("total sum of user count on each subscription is equal to total user count",function(){

    $this->subscriptions->assertOk();

    $total = User::count();

    $sum = $this->subscriptions->json('total_user_subscription');

    $this->assertEquals($total,$sum);
});

test("request body validation in creating subscription",function(){
    $response = $this->authenticatedAdmin()->postJson(route('api.admin.subscriptions.store'),[]);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message' ,
            'errors'
        ]);
});

test("show subscription return success response",function(){
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.subscriptions.show',[
        'subscription' => 1
    ]));

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'subscription'
    ]);

});

test("return 404 on non-existed subscription",function(){
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.subscriptions.show',[
        'subscription' => 1000
    ]));

    $response->assertStatus(404);
})->group('new');


test("create subscription",function($title, $max, $duration){

    $data = [
        'title' => $title,
        'price' => 100,
        'max' => $max,
        'duration' => $duration,
    ];

    $response = $this->authenticatedAdmin()->postJson(route('api.admin.subscriptions.store'),$data);

    $response->assertStatus(201)
        ->assertJson([
            'subscription' => $data
    ]);

    $this->assertDatabaseHas('subscriptions',$data);

})
->with([
    ['test_subscription', 100, 30],
    ['test_subscription2', 200, 60]
]);

test("request body required in updating subscription",function(){
    $response = $this->authenticatedAdmin()->putJson(route('api.admin.subscriptions.update',1),[]);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message' ,
            'errors'
        ]);
});

test("can update subscription",function($title, $max, $duration){
    $data = [
        'title' => $title,
        'max' => $max,
        'price' => 100,
        'duration' => $duration,
    ];

    $response = $this->authenticatedAdmin()->putJson(route('api.admin.subscriptions.update',1),$data);

    $response->assertStatus(200)
        ->assertJson([
            'subscription' => $data
    ]);
    $this->assertDatabaseHas('subscriptions',$data);
})
->with([
    ['test_subscription', 100, 30],
]);

test("can update same subscription with same title",function($title, $max, $duration){
    $data = [
        'title' => $title,
        'price' => 100,
        'max' => $max,
        'duration' => $duration,
    ];

    $new_subscription = $this->authenticatedAdmin()->postJson(route('api.admin.subscriptions.store'),$data);

    $new_subscription_id = $new_subscription->json('subscription.id');

    $new_body = [
        'title' => $title,
        'price' => 100,
        'max' => 200,
        'duration' => 60,
    ];

    $response = $this->authenticatedAdmin()->putJson(route('api.admin.subscriptions.update',$new_subscription_id),$new_body);

    $response->assertStatus(200)
        ->assertJson([
            'subscription' => $new_body
    ]);

    $this->assertDatabaseHas('subscriptions',$new_body);
})
->with([
    ['test_subscription', 100, 30],
]);

test("can't update the duplicate title",function($title, $max, $duration){

    $new_body = [
        'title' => $title,
        'price' => 100,
        'max' => 200,
        'duration' => 60,
    ];

    $this->authenticatedAdmin()->postJson(route('api.admin.subscriptions.store'),$new_body);

    $response = $this->authenticatedAdmin()->putJson(route('api.admin.subscriptions.update',1),$new_body);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'The title has already been taken.'
    ]);

})
->with([
    ['test_subscription', 100, 30],
]);

test("can't update because subscription not found",function($title, $max, $duration){

    $new_body = [
        'title' => $title,
        'price' => 100,
        'max' => 200,
        'duration' => 60,
    ];

    $response = $this->authenticatedAdmin()->putJson(route('api.admin.subscriptions.update',1000242),$new_body);

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'Subscription not found'
    ]);

})
->with([
    ['test_subscription', 100, 30],
]);

test("can't delete because subscription not found",function($id){

        $response = $this->authenticatedAdmin()->postJson(route('api.admin.subscriptions.delete',$id));

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Subscription not found'
        ]);
})
->with([
    1000242,
    1002424
]);

test("can delete subscription",function($id){

    $response = $this->authenticatedAdmin()->postJson(route('api.admin.subscriptions.delete',$id));

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Subscription deleted successfully.'
    ]);

    $this->assertDatabaseMissing('subscriptions',['id' => $id]);
})
->with([
    1,
    2
])->group('new');
