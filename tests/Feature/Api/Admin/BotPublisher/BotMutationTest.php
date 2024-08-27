<?php

use App\Enum\SocialMediaType;
use Tests\Support\UserAuthenticated;

uses()->group('admin','api','bot-publisher','bot-mutation');
uses(UserAuthenticated::class);


beforeEach(function(){
    $this->setupAdmin();
});

test("Body validation was validate for creating new bot",function(){
    $response = $this->postJson(route('api.admin.bot-publisher.store'),[]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'name',
        'token_key',
        'type',
        'available_ids'
    ]);
});

test("Bot publisher was created successfully",function(){
    $response = $this->postJson(route('api.admin.bot-publisher.store'),[
        'name' => 'Bot Publisher 1',
        "token_key" => "token_key",
        'type' => SocialMediaType::Telegram->value,
        'available_ids' => "12,2,21",
    ]);
    $response->assertStatus(200);

    $response->assertJsonStructure([
        'message',
        'bot'
    ]);
});
