<?php

use function Pest\Stressless\stress;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogousCategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;
use Database\Seeders\SubscriptionSeeder;

beforeEach(function() {
    config(['control.test.mogous_count' => 20]);

    // Seed the database
    $this->seed([
        SubscriptionSeeder::class,
        CategorySeeder::class,
        MogouSeeder::class,
        MogousCategorySeeder::class,
        SubMogouSeeder::class,
    ]);

});

uses()->group('stress-test');

it('home page carousel response under 100ms ', function () {
    // $result = stress('127.0.0.1:8000');s
    $host =route('api.users.carousel');

    $result = stress($host)->concurrency(100)->for(3)->seconds();

    expect($result->requests()->failed()->rate())->toBeLessThan(2);

    expect($result->requests()->duration()->med())->toBeLessThan(100); // < 100.00ms
})->group('stress-test');

it("most-viewed mogous response under 100ms",function(){
    $host =route('api.users.most-viewed');

    $result = stress($host)->concurrency(100)->for(3)->seconds();

    expect($result->requests()->failed()->rate())->toBeLessThan(2);

    expect($result->requests()->duration()->med())->toBeLessThan(100); // < 100.00ms
});


