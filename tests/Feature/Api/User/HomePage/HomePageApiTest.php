<?php

use Database\Seeders\BaseSectionSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogousCategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;
use Database\Seeders\SubscriptionSeeder;
use Database\Seeders\UserAvatarSeeder;
use Tests\Support\UserAuthenticated;

// Group the test
uses()->group('user','api','homepage');
uses(UserAuthenticated::class);

beforeEach(function() {
    config(['control.test.mogous_count' => 20]);

    // Seed the database
    $this->seed([

        SubscriptionSeeder::class,
        CategorySeeder::class,
        MogouSeeder::class,
        MogousCategorySeeder::class,
        SubMogouSeeder::class,
        BaseSectionSeeder::class,
    ]);

    $this->setupUser();
});

test("carousel data for homepage can fetched successfully",function(){
    $response = $this->getJson(route('api.users.carousel'));

    $response->assertOk();
    $count = count($response->json('mogous'));
    $this->assertTrue($count > 0);
});

test("most-viewed mogous data for homepage can fetched successfully",function(){
    $response = $this->getJson(route('api.users.most-viewed'));

    $response->assertOk();
    $count = count($response->json('mogous'));
    $this->assertTrue($count > 1);
});

test("last-uploaded mogous data for homepage can fetched successfully",function(){
    $response = $this->getJson(route('api.users.last-uploaded'));

    $response->assertOk();
    $count = count($response->json('mogous.data'));
    $this->assertTrue($count > 1);
});
