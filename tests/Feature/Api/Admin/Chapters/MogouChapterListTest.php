<?php

use App\Models\SubMogou;
use App\Models\SubMogouImage;
use App\Services\Partition\PartitionFactory;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogousCategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;
use Illuminate\Support\Facades\DB;
use Tests\Support\UserAuthenticated;

uses()->group('admin','api','admin-mogou','admin-mogou-chapters');
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

it("mogou chapters can be fetched", function(){
    $mogou = \App\Models\Mogou::first();

    $tables = ((new SubMogouImage())->getCreatedPartitions());

    \App\Models\SubMogouImage::factory()->count(20)->create([
        'sub_mogou_id' => 1
    ]);

    PartitionFactory::shareData('sub_mogou_images',$tables[1]);

    $response = $this->getJson(route('api.admin.mogou-chapters.index',['mogou' => $mogou->slug]));
    $response->assertOk();

});

it("Chapter analysis can be fetched", function(){
    $mogou = \App\Models\Mogou::first();

    $response = $this->getJson(route('api.admin.mogou-chapters.chapterAnalysis',['mogou' => $mogou->slug]));

    $response->assertOk();

});
