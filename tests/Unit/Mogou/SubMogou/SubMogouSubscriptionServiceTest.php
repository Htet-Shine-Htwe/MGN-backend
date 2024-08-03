<?php

use Database\Seeders\CategorySeeder;
use Database\Seeders\MogousCategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;
use Database\Seeders\SubscriptionSeeder;

uses()->group('unit','mogou-subscription');

beforeEach(function(){

    config(['control.test.mogous_count' => 10]);

    $this->seed([
        SubscriptionSeeder::class,
        CategorySeeder::class,
        MogouSeeder::class,
        MogousCategorySeeder::class,
        SubMogouSeeder::class
    ]);
});


test('Check SubMogouSubscription class exists', function () {
    $this->assertTrue(class_exists(\App\Services\Subscription\SubMogouSubscription::class));
});


test("get the collection of subscription ids",function(){

    $ids = [1,2,3,4,5];

    $subMogou = \App\Models\SubMogou::factory()->create([
        'subscription_collection' => json_encode($ids)
    ]);


    $this->assertEquals($ids,$subMogou->subscription_collection);

});

test("append single subscription id to the collection",function(){

    $ids = [1,2,3,4,5];

    $subMogou = \App\Models\SubMogou::factory()->create([
        'subscription_collection' => json_encode($ids)
    ]);
    $subMogouSubscription = new \App\Services\Subscription\SubMogouSubscription($subMogou);

    $subMogouSubscription->appendSubscriptionId(6);

    $this->assertEquals([1,2,3,4,5,6],$subMogou->subscription_collection);

});


test("remove single subscription id from the collection", function() {
    $ids = [1, 2, 3, 4, 5];

    $subMogou = \App\Models\SubMogou::factory()->create([
        'subscription_collection' => json_encode($ids)
    ]);
    $subMogouSubscription = new \App\Services\Subscription\SubMogouSubscription($subMogou);

    $subMogouSubscription->removeSubscriptionId(3);

    $this->assertEquals([1, 2, 4, 5], $subMogou->subscription_collection);
});


test("append multiple subscription ids to the collection",function(){

    $ids = [1,2,3,4,5];

    $subMogou = \App\Models\SubMogou::factory()->create([
        'subscription_collection' => json_encode($ids)
    ]);
    $subMogouSubscription = new \App\Services\Subscription\SubMogouSubscription($subMogou);

    $subMogouSubscription->appendSubscriptionId([6,7,8]);

    $this->assertEquals([1,2,3,4,5,6,7,8],$subMogou->subscription_collection);

});


test("remove multiple subscription ids from the collection", function() {
    $ids = [1, 2, 3, 4, 5];

    $subMogou = \App\Models\SubMogou::factory()->create([
        'subscription_collection' => json_encode($ids)
    ]);
    $subMogouSubscription = new \App\Services\Subscription\SubMogouSubscription($subMogou);

    $subMogouSubscription->removeSubscriptionId([3,5]);

    $this->assertEquals([1, 2, 4], $subMogou->subscription_collection);
});
