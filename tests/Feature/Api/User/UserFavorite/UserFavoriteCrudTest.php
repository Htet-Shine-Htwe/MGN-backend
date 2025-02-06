<?php

use Database\Seeders\CategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubscriptionSeeder;
use Database\Seeders\UserSeeder;
use Tests\Support\UserAuthenticated;

// Group the test
uses()->group('user','api','user-favorite');
uses(UserAuthenticated::class);

beforeEach(function() {
    // Set configuration
    config(['control.test.mogous_count' => 20]);

    // Seed the database
    $this->seed([
        SubscriptionSeeder::class,
        UserSeeder::class,
        CategorySeeder::class,
        MogouSeeder::class,
    ]);

    $this->setupUser();

});

test("list user favorites",function()
{

    $this->postJson(route('api.users.user-favorites.store',[
        'mogou_id' => 1
    ]));

    $response = $this->getJson(route('api.users.user-favorites.index'));

    $response->assertOk();

    $response->assertJsonStructure([
        'favorites'
    ]);

});

test("user can add favorite",function()
{
    $response = $this->postJson(route('api.users.user-favorites.store',[
        'mogou_id' => 1
    ]));

    $response->assertOk();
    $response->assertJson([
        'message' => 'Favorite added'
    ]);
});

test("duplicate favorite doesn't add to favorites",function()
{
    $this->postJson(route('api.users.user-favorites.store',[
        'mogou_id' => 1
    ]));

    $response = $this->postJson(route('api.users.user-favorites.store',[
        'mogou_id' => 1
    ]));

    $response->assertStatus(400);

    $response->assertJson([
        'message' => 'Already added'
    ]);

});

test("user can remove favorite",function()
{
    $this->postJson(route('api.users.user-favorites.store',[
        'mogou_id' => 1
    ]));

    $response = $this->postJson(route('api.users.user-favorites.delete',[
        'mogou_id' => 1
    ]));

    $response->assertOk();
    $response->assertJson([
        'message' => 'Favorite removed'
    ]);
});

test("user can't remove non-existing favorite",function()
{
    $response = $this->postJson(route('api.users.user-favorites.delete',[
        'mogou_id' => 30303
    ]));

    $response->assertStatus(422);
    $response->assertJson([
        'message' => 'The selected mogou id is invalid.'
    ]);
});
