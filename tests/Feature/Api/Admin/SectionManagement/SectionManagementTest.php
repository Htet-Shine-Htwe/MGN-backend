<?php

use App\Models\ChildSection;
use Database\Seeders\BaseSectionSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogousCategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;
use Tests\Support\UserAuthenticated;

uses()->group('admin','api','section-management');
uses( UserAuthenticated::class);

beforeEach(function(){

    config(['control.test.mogous_count' => 20]);

    $this->seed([
        CategorySeeder::class,
        MogouSeeder::class,
        MogousCategorySeeder::class,
        SubMogouSeeder::class,
        BaseSectionSeeder::class,
    ]);
    $this->setupAdmin();
});


it("can get hero_highlight_slider section",function()
{

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.sections.index',[
        'section' => 'hero_highlight_slider'
    ]));

    $response->assertOk();
    $response->assertJsonStructure([
        'baseSection',
    ]);
});

it("can attach new child to hero_highlight_slider section",function(){

        $response = $this->authenticatedAdmin()->postJson(route('api.admin.sections.update',[
            'section' => 'hero_highlight_slider'
        ]),[
            'child' => 3
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'message',
        ]);
});

it("can't add child to hero_highlight_slider section if it exceeds the limit",function(){
    ChildSection::factory(3)->create([
        "base_section_id" => 1,
    ]);

    $response = $this->authenticatedAdmin()->postJson(route('api.admin.sections.update',[
        'section' => 'hero_highlight_slider'
    ]),[
        'child' => 3
    ]);

    $response->assertStatus(500);
    $response->assertJsonStructure([
        'message',
    ]);
});
