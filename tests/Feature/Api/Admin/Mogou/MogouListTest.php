<?php

use App\Enum\MogouFinishStatus;
use App\Enum\MogouTypeEnum;
use App\Models\Mogou;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogousCategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;
use Illuminate\Support\Facades\Schema;
use Tests\Support\UserAuthenticated;

uses()->group('admin','api','admin-mogou','admin-mogou-list');
uses(UserAuthenticated::class);

beforeEach(function(){

    config(['control.test.mogous_count' => 20]);

    $this->seed([
        CategorySeeder::class,
        MogouSeeder::class,
        MogousCategorySeeder::class,
        SubMogouSeeder::class
    ]);
    $this->setupAdmin();

});

dataset('mogou-data-collection',[
    fn() => [
        'title' => 'mogou alpha',
        'status' => 2,
        'released_year' => 2020
    ],
    fn() => [
        'title' => 'mogou beta',
        'status' => 2,
        'released_year' => 2020
    ],
]);


test("mogou and mogou category table exist",function()
{
    $this->assertTrue(Schema::hasTable('mogous'));
    $this->assertTrue(Schema::hasTable('mogous_categories'));
});

test("mogou data can be fetched",function()
{
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index'));

    $response->assertOk();
    $response->assertJsonCount(10,'mogous.data');

});

test("mogou data can searched with title",function($data)
{
    Mogou::factory()->create([
        'title' => $data['title']
    ]);

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'search' => $data['title']
    ]));

    $response->assertOk();
    $response->assertJsonCount(1,'mogous.data');



})->with('mogou-data-collection');

test("mogou data can be filtered by status",function($data)
{
    Mogou::factory()->create([
        'status' => $data['status']
    ]);

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'status' => $data['status']
    ]));

    $response->assertOk();

    $mogous = $response->json('mogous.data');

    $this->assertNotEmpty($mogous);

    collect($mogous)->each(function($mogou) use ($data){
        $this->assertEquals($data['status'],$mogou['status']);
    });

})->with('mogou-data-collection');

test("mogou data can be filtered by release year",function($data)
{
    Mogou::factory()->create([
        'released_year' => $data['released_year']
    ]);

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'year' => $data['released_year']
    ]));

    $response->assertOk();

    $mogous = $response->json('mogous.data');

    $this->assertNotEmpty($mogous);

    collect($mogous)->each(function($mogou) use ($data){
        $this->assertEquals($data['released_year'],$mogou['released_year']);
    });

})->with('mogou-data-collection');

test("mogou data filtered with status & year",function($data)
{
    Mogou::factory()->create([
        'status' => $data['status'],
        'released_year' => $data['released_year']
    ]);

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'status' => $data['status'],
        'year' => $data['released_year']
    ]));

    $response->assertOk();

    $mogous = $response->json('mogous.data');

    $this->assertNotEmpty($mogous);

    collect($mogous)->each(function($mogou) use ($data){
        $this->assertEquals($data['status'],$mogou['status']);
        $this->assertEquals($data['released_year'],$mogou['released_year']);
    });

})->with('mogou-data-collection');

test("mogou data filtered with status & category & year",function($data){

    $mogou = Mogou::factory()->create([
        'status' => $data['status'],
        'released_year' => $data['released_year']
    ]);

    $category = \App\Models\Category::factory()->create();

    $mogou->categories()->attach($category->id);

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'status' => $data['status'],
        'category' => $category->id,
        'year' => $data['released_year']
    ]));

    $response->assertOk();

    $mogous = $response->json('mogous.data');

    $this->assertNotEmpty($mogous);

    collect($mogous)->each(function($mogou) use ($data){
        $this->assertEquals($data['status'],$mogou['status']);
        $this->assertEquals($data['released_year'],$mogou['released_year']);
    });

})->with('mogou-data-collection');


test("Mogou collection order by rating",function()
{
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'order_by_rating' => 'desc'
    ]));

    $response->assertOk();

    $mogous = $response->json('mogous.data');

    $this->assertNotEmpty($mogous);

    for($i = 0; $i < count($mogous) - 1; $i++){
        $this->assertLessThanOrEqual($mogous[$i]['rating'],$mogous[$i+1]['rating']);
    }

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'order_by_rating' => 'asc'
    ]));

    $mogous = $response->json('mogous.data');

    $this->assertNotEmpty($mogous);

    for($i = 0; $i < count($mogous) - 1; $i++){
        $this->assertGreaterThanOrEqual($mogous[$i+1]['rating'],$mogous[$i]['rating']);
    }

});

test("Only Age Legal Mogou returned",function(){

        $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
            'legal_only' => true
        ]));

        $response->assertOk();

        $mogous = $response->json('mogous.data');

        $this->assertNotEmpty($mogous);

        collect($mogous)->each(function($mogou){
            $this->assertEquals(0,$mogou['legal_age']);
        });
})->group("hi");

test("Only Completed Mogou returned",function(){

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'finish_status' => "Completed"
    ]));

    $response->assertOk();

    $mogous = $response->json('mogous.data');

    $this->assertNotEmpty($mogous);

    collect($mogous)->each(function($mogou){
        $this->assertEquals(MogouFinishStatus::COMPLETED->value,$mogou['finish_status']);
    });
});

test("Only Manhwa Mogou returned",function(){

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'mogou_type' => "Manhwa"
    ]));

    $response->assertOk();

    $mogous = $response->json('mogous.data');

    $this->assertNotEmpty($mogous);

    collect($mogous)->each(function($mogou){
        $this->assertEquals(MogouTypeEnum::MANHWA->value,$mogou['mogou_type']);
    });
});

test("Only Completed Manhwa Mogou returned",function(){

    Mogou::factory()->create([
        'mogou_type' => MogouTypeEnum::MANHWA->value,
        'finish_status' => MogouFinishStatus::COMPLETED->value
    ]);

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.mogous.index',[
        'mogou_type' => "Manhwa",
        'finish_status' => "Completed"
    ]));

    $response->assertOk();

    $mogous = $response->json('mogous.data');

    $this->assertNotEmpty($mogous);

    collect($mogous)->each(function($mogou){
        $this->assertEquals(MogouTypeEnum::MANHWA->value,$mogou['mogou_type']);
        $this->assertEquals(MogouFinishStatus::COMPLETED->value,$mogou['finish_status']);
    });
});
