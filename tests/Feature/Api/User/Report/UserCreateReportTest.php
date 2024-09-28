<?php

use Illuminate\Http\UploadedFile;
use Tests\Support\TestStorage;
use Tests\Support\UserAuthenticated;


uses()->group('user','api','user-report');

uses(TestStorage::class);
uses(UserAuthenticated::class);

it("Validation error when creating report", function(){

    $response = $this->postJson(route('api.users.reports.create'),[
        'current_url' => 'https://test.com',
    ]);

    $response->assertStatus(422);
});


it("Anonymously user can create report", function(){
    $response = $this->postJson(route('api.users.reports.create'),[
        "title" => "Test Report",
        "description" => "Test Description",
        "current_url" => "https://test.com",
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'message'
    ]);

    $this->assertDatabaseHas('reports',[
        'title' => 'Test Report',
        'description' => 'Test Description',
        'current_url' => 'https://test.com',
    ]);
});

it("Authorized user can create report", function(){
    $response = $this->setupUser([
        'current_subscription_id' => null,
    ])->postJson(route('api.users.reports.create'),[
        "title" => "Test Report",
        "description" => "Test Description",
        "current_url" => "https://test.com",
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'message',
        "data"
    ]);

    $this->assertDatabaseHas('reports',[
        'title' => 'Test Report',
        'description' => 'Test Description',
        'current_url' => 'https://test.com',
        'user_id' => $this->user->id,
    ]);
});

it("Image was stored when creating report", function(){
    $this->bootStorage();


    $response = $this->postJson(route('api.users.reports.create'),[
        "title" => "Test Report",
        "description" => "Test Description",
        "current_url" => "https://test.com",
        "image" => UploadedFile::fake()->image('cover.jpg'),
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'message',
        "data"
    ]);

    $this->assertDatabaseHas('reports',[
        'title' => 'Test Report',
        'description' => 'Test Description',
        'current_url' => 'https://test.com',
        'image' => 'cover.jpg'
    ]);

    $this->assertInStorage('reports/'.$response->json('cover'));

});
