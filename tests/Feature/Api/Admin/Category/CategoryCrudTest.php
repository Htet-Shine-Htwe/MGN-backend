<?php

use Database\Seeders\CategorySeeder;
use Database\Seeders\MogousCategorySeeder;
use Database\Seeders\MogouSeeder;
use Illuminate\Support\Facades\Schema;
use Tests\Support\UserAuthenticated;


uses()->group('admin','api','admin-category');
uses(UserAuthenticated::class);



beforeEach(function(){
    config(['control.test.users_count' => 10]);
    config(['control.test.mogous_count' => 50]);

    $this->seed([
        CategorySeeder::class,
        MogouSeeder::class,
        MogousCategorySeeder::class,
    ]);
    $this->setupAdmin();

    $this->categories = $this->authenticatedAdmin()->getJson(route('api.admin.categories.index'));

});

test("category table exists",function(){
    $this->assertTrue(Schema::hasTable('categories'));
});

test("check category have expected count 10",function(){

    $this->categories->assertOk();

    $this->categories->assertJsonCount(10,'categories.data');
});

test("each category has mogous count and order by much popular",function(){

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.categories.index',[
        'order_by_mogous_count' => 'desc'
    ]));

    $response->assertJsonStructure([
        'categories' => [
            'data' => [
                '*' => [
                    'mogous_count'
                ]
            ]
        ]
    ]);

    $mogou = $response['categories']['data'];

    for($i = 0; $i < count($mogou) - 1; $i++){
        $this->assertLessThanOrEqual($mogou[$i]['mogous_count'],$mogou[$i+1]['mogous_count']);
    }
});

test("each category has mogous count and order by less popular",function(){

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.categories.index',[
        'order_by_mogous_count' => 'asc'
    ]));


    $mogou = $response['categories']['data'];

    for($i = 0; $i < count($mogou) - 1; $i++){
        $this->assertGreaterThanOrEqual($mogou[$i]['mogous_count'],$mogou[$i+1]['mogous_count']);
    }
});


test("request body validation in creating category",function(){
    $response = $this->authenticatedAdmin()->postJson(route('api.admin.categories.store'),[]);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors'
        ]);
});

test("category was successfully created & slug was correctly-formatted",function(){
    $title = "New Category";
    $response = $this->authenticatedAdmin()->postJson(route('api.admin.categories.store'),[
        'title' => $title
    ]);

    $slug = \Illuminate\Support\Str::slug($title);

    $response->assertStatus(201);

    $response->assertJsonStructure([
            'category'
        ]);
    $response->assertJson([
        'category' => [
            'title' => $title,
            'slug' => $slug
        ]
    ]);

});


test("request body validation in updating category",function(){
    $response = $this->authenticatedAdmin()->putJson(route('api.admin.categories.update',1),[]);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors'
        ]);
});

test("can update category successfully",function(){
    $new_title = "New Title";
    $response = $this->authenticatedAdmin()->putJson(route('api.admin.categories.update',1),[
        'title' => $new_title
    ]);

    $slug = \Illuminate\Support\Str::slug($new_title);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'category'
    ]);
    $response->assertJson([
        'category' => [
            'title' => $new_title,
            'slug' => $slug
        ]
    ]);
});

test("can't update due to non-existed cateegoory",function(){
    $response = $this->authenticatedAdmin()->putJson(route('api.admin.categories.update',100),[
        'title' => "New Title"
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'Category not found'
        ]);
});

test("can delete category",function(){
    $response = $this->authenticatedAdmin()->postJson(route('api.admin.categories.delete',1));

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Category deleted successfully.'
        ]);
    $this->assertDatabaseMissing('categories',[
        'id' => 1
    ]);
});

test("can't delete due to non-existed category",function(){
    $response = $this->authenticatedAdmin()->postJson(route('api.admin.categories.delete',100));

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'Category not found'
        ]);
});
