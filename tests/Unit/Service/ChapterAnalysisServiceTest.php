<?php

use App\Models\ChapterAnalysis;
use App\Models\SubMogou;
use App\Services\ChapterAnalysis\ChapterAnalysisService;
use App\Traits\FakeIpHeader;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;
use Illuminate\Support\Facades\Cache;

uses()->group('service', 'chapter-analysis-service');

beforeEach(function () {
    Cache::flush();
    ChapterAnalysis::truncate();
    $this->service = new ChapterAnalysisService();

    config(['control.test.mogous_count' => 20]);

    $this->seed([
        CategorySeeder::class,
        MogouSeeder::class,
        SubMogouSeeder::class
    ]);

    $this->mogou_id = 10;
    $this->sub_mogou_id = 22;
    $this->client_ip = "127.0.0.1";

    $this->requestCacheKey = "chapter_view:{$this->mogou_id}:{$this->sub_mogou_id}:{$this->client_ip}";

});

// uses(FakeIpHeader::class);

// it('stores a new chapter view if not cached', function () {
//     $this->setupHeader($this->client_ip);

//     $subMogou = SubMogou::findOrFail($this->sub_mogou_id);

//     $response = $this->service->storeRecord($subMogou);

//     expect($response->getStatusCode())->toBe(200);
//     expect($response->getData(true))->toMatchArray([
//         'message' => 'Chapter viewed',
//         'expires_in' => 60, // Ensure it matches service value
//     ]);
//     expect(Cache::has($this->requestCacheKey))->toBeTrue();

//     $this->assertDatabaseHas('chapter_analyses', [
//         'mogou_id' => $this->mogou_id,
//         'sub_mogou_id' => $this->sub_mogou_id,
//     ]);
// });

// it('prevents duplicate views within cache expiration time', function () {
//     $subMogou = SubMogou::findOrFail($this->sub_mogou_id);

//     Cache::put($this->requestCacheKey, true, 60);

//     $response = $this->service->storeRecord($subMogou);

//     expect($response->getStatusCode())->toBe(200);
//     expect($response->getData(true))->toMatchArray([
//         'message' => 'Chapter already viewed',
//     ]);

//     // Ensure no additional database entry is created
//     expect(ChapterAnalysis::count())->toBe(0);
// });

// it('allows viewing different chapters', function () {
//     $subMogou1 = SubMogou::findOrFail($this->sub_mogou_id);
//     $subMogou2 = SubMogou::where('id', '!=', $this->sub_mogou_id)->firstOrFail(); // Get a different chapter

//     // First chapter view
//     $this->service->storeRecord($subMogou1);
//     expect(Cache::has($this->requestCacheKey))->toBeTrue();

//     // Second chapter view (different sub_mogou_id)
//     $response = $this->service->storeRecord($subMogou2);

//     expect($response->getData(true))->toMatchArray([
//         'message' => 'Chapter viewed',
//         'expires_in' => 60,
//     ]);

//     // Two records should exist in the database
//     expect(ChapterAnalysis::count())->toBe(2);
// });

// it('allows re-viewing a chapter after cache expiration', function () {
//     $subMogou = SubMogou::findOrFail($this->sub_mogou_id);

//     $this->service->storeRecord($subMogou);

//     // Manually expire cache
//     Cache::forget($this->requestCacheKey);

//     // Second view after cache expiration
//     $response = $this->service->storeRecord($subMogou);

//     expect($response->getData(true))->toMatchArray([
//         'message' => 'Chapter already viewed',
//         'expires_in' => 60,
//     ]);

//     // Two records should exist
//     expect(ChapterAnalysis::count())->toBe(2);
// });
