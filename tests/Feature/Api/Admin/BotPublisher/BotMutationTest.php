<?php

use App\Enum\SocialMediaType;
use App\Models\BotPublisher;
use App\Services\BotPublisher\CreateBot;
use Mockery\MockInterface;
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
    ]);
});


// test("Bot publisher was created successfully", function () {

//     // Perform the request
//     $response = $this->postJson(route('api.admin.bot-publisher.store'), [
//         'name' => 'Bot Publisher 1',
//         "token_key" => "token_key",
//         'type' => SocialMediaType::Telegram->value,
//     ]);

//     $response->assertStatus(200);

//     $response->assertJsonStructure([
//         'message',
//         'bot',
//     ]);

//     // Assert JSON data
//     $response->assertJson([
//         'message' => 'Bot was created Successfully',
//         'bot' => [
//             'name' => 'Bot Publisher 1',
//             'token_key' => 'token_key',
//             'type' => SocialMediaType::Telegram->value,
//             'is_active' => true,
//         ],
//     ]);
// });
