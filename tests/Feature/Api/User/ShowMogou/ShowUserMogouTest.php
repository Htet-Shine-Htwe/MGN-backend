<?php

use App\Models\Mogou;
use App\Models\SubMogou;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubscriptionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;
use Tests\Support\UserAuthenticated;

// Group the test
uses()->group('user','api','show-user-mogou');
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

test("show mogou can be found with slug", function() {
    // Assuming 'some-mogou-slug' is a valid slug you want to test

    $mogou = Mogou::factory()->create([
        'rotation_key' => "alpha"
    ]);

    $subMogou = new SubMogou();

    $subMogou->setTable("alpha_sub_mogous");

    SubMogou::factory()->create([
        'mogou_id' => $mogou->id
    ]);

    DB::statement('insert into alpha_sub_mogous select * from sub_mogous');

    $subMogou->factory(2)->create([
        'mogou_id' => $mogou->id
    ]);

    $response = $this->getJson(route('api.users.mogous.show', [
        'mogou' => $mogou->slug
    ]));

    $response->assertOk();
    $response->assertJsonStructure([
        'mogou',
        'is_favorite',
        'chapters'
    ]);
});

test("retrieves related latest mogou based on categories",function(){
    // Assuming 'some-mogou-slug' is a valid slug
    $mogou = Mogou::factory()->create([
        'rotation_key' => "alpha"
    ]);

    $subMogou = new SubMogou();

    $subMogou->setTable("alpha_sub_mogous");

    SubMogou::factory()->create([
        'mogou_id' => $mogou->id
    ]);

    DB::statement('insert into alpha_sub_mogous select * from sub_mogous');

    $mogou->categories()->attach(1);

    $relatedMogous = Mogou::factory(6)->create();

    $relatedMogous->each(function($relatedMogou){
        $relatedMogou->categories()->attach(1);
    });

    $response = $this->getJson(route('api.users.mogous.relateMogou', [
        'mogou' => $mogou->slug
    ]));

    $response->assertOk();
    $response->assertJsonStructure([
        'mogous'
    ]);
    $response->assertJsonCount(6,'mogous');
});
