<?php

use App\Services\Mogou\MogouService;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ChapterAnalysisSeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;

uses()->group('service', 'mogou-service');

beforeEach(function () {
    $this->service = new MogouService();
    config(['control.test.mogous_count' => 20]);
    $this->seed([
        CategorySeeder::class,
        MogouSeeder::class,
        SubMogouSeeder::class,
        ChapterAnalysisSeeder::class
    ]);

});

it("getMogouByPopularity return the ids of mogous popular within 30days",function(){    
    $mogous = $this->service->getMogouByPopularity();

    $this->assertIsArray($mogous);
});