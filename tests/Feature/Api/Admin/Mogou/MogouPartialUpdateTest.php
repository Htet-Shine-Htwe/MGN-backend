<?php

use App\Enum\MogousStatus;
use App\Models\Mogou;
use Database\Seeders\CategorySeeder;
use Tests\Support\UserAuthenticated;

uses()->group('admin','api','admin-mogou','admin-mogou-partial-update');
uses(UserAuthenticated::class);

beforeEach(function(){
    $this->seed([
        CategorySeeder::class
    ]);
    $this->setupAdmin();
    $this->sampleJsonStructure = [
        'id',
        'title',
        'slug',
        'description',
        'author',
        'cover',
        'status',
        'finish_status',
        'legal_age',
        'rating',
        'released_year',
        'released_at',
        'categories',
    ];
    $this->mogou =  Mogou::factory()->create();
});

test("status field is required when updating mogou status",function(){
    $response = $this->postJson(route('api.admin.mogous.updateStatus'),[
        'mogou_id'=> $this->mogou->id,
        'status' => null
    ]);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('status');
});

test("can update mogous status successfully ",function(){
    $response = $this->postJson(route('api.admin.mogous.updateStatus'),[
        'mogou_id'=> $this->mogou->id,
        'status' => MogousStatus::PUBLISHED->value
    ]);
    $response->assertStatus(200);
    $this->assertDatabaseHas('mogous',[
        'id' => $this->mogou->id,
        'status' => MogousStatus::PUBLISHED->value
    ]);
});

test("category id is required when adding new category to mogou",function(){
    $response = $this->postJson(route('api.admin.mogous.addCategory'),[
        'mogou_id'=> $this->mogou->id,
        'category_id' => null
    ]);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('category_id');
});

test("can't add new category to mogou if category id is invalid",function(){
    $response = $this->postJson(route('api.admin.mogous.addCategory'),[
        'mogou_id'=> $this->mogou->id,
        'category_id' => 999
    ]);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('category_id');
});

test("can add new category to mogou successfully ",function(){
    $response = $this->postJson(route('api.admin.mogous.addCategory'),[
        'mogou_id'=> $this->mogou->id,
        'category_id' => 5
    ]);
    $response->assertStatus(200);
    $this->assertDatabaseHas('mogous_categories',[
        'mogou_id' => $this->mogou->id,
        'category_id' => 5
    ]);
});

test("can't add new category to mogou if category already exists",function(){
    $this->mogou->categories()->attach(5);
    $response = $this->postJson(route('api.admin.mogous.addCategory'),[
        'mogou_id'=> $this->mogou->id,
        'category_id' => 5
    ]);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('category_id');
});

test("category id is required when removing category from mogou",function(){
    $response = $this->postJson(route('api.admin.mogous.removeCategory'),[
        'mogou_id'=> $this->mogou->id,
        'category_id' => null
    ]);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('category_id');
});

test("can't remove category from mogou if category id is invalid",function(){
    $response = $this->postJson(route('api.admin.mogous.removeCategory'),[
        'mogou_id'=> $this->mogou->id,
        'category_id' => 999
    ]);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('category_id');
});

test("can remove category from mogou successfully ",function(){
    $this->mogou->categories()->attach(5);
    $response = $this->postJson(route('api.admin.mogous.removeCategory'),[
        'mogou_id'=> $this->mogou->id,
        'category_id' => 5
    ]);
    $response->assertStatus(200);
    $this->assertDatabaseMissing('mogous_categories',[
        'mogou_id' => $this->mogou->id,
        'category_id' => 5
    ]);
});
