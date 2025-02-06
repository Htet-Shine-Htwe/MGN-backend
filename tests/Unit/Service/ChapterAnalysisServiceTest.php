<?php

use App\Models\ChapterAnalysis;
use App\Services\ChapterAnalysis\ChapterAnalysisService;
use App\Traits\FakeIpHeader;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

uses()->group('service','chapter-analysis-service');

beforeEach(function () {
    Cache::flush(); // Ensure cache is cleared before each test
    ChapterAnalysis::truncate(); // Clear database table before each test
    $this->service = new ChapterAnalysisService();

    config(['control.test.mogous_count' => 20]);

    $this->seed([
        CategorySeeder::class,
        MogouSeeder::class,
        SubMogouSeeder::class
    ]);

    $this->mogou_id = 10;
    $this->sub_mogou_id = 22;
    $this->client_ip = "192.168.1.1";

    $this->requestCacheKey = "chapter_view:{$this->mogou_id}:{$this->sub_mogou_id}:{$this->client_ip}";
});

uses(FakeIpHeader::class);


it('stores a new chapter view if not cached', function () {
    $request = $this->setupHeader();

    $response = $this->service->storeRecord($request);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData(true))->toMatchArray([
        'message' => 'Chapter viewed',
        'expires_in' => 180,
    ]);
    expect(Cache::has($this->requestCacheKey))->toBeTrue();

    $this->assertDatabaseHas('chapter_analyses', [
        'mogou_id' => $this->mogou_id,
        'sub_mogou_id' => $this->sub_mogou_id,
    ]);
});

it('prevents duplicate views within cache expiration time', function () {
    $request = $this->setupHeader();

    Cache::put($this->requestCacheKey, true, 180);

    $response = $this->service->storeRecord($request);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData(true))->toMatchArray([
        'message' => 'Chapter already viewed',
    ]);

    // Ensure no additional database entry is created
    expect(ChapterAnalysis::count())->toBe(0);
});

it('allows viewing different chapters', function () {
    $request = $this->setupHeader();

    $request2 = new Request([
        'mogou_id' => 11,
        'sub_mogou_id' => 29, // Different sub_mogou_id
    ]);
    $request2->server->set('REMOTE_ADDR', '192.168.1.1');

    // First chapter view
    $this->service->storeRecord($request);
    expect(Cache::has($this->requestCacheKey))->toBeTrue();

    // Second chapter view (different sub_mogou_id)
    $response = $this->service->storeRecord($request2);

    expect($response->getData(true))->toMatchArray([
        'message' => 'Chapter viewed',
        'expires_in' => 180,
    ]);

    // Two records should exist in the database
    expect(ChapterAnalysis::count())->toBe(2);
});

it('allows re-viewing a chapter after cache expiration', function () {
    $request = $this->setupHeader();

    $this->service->storeRecord($request);

    // Manually expire cache
    Cache::forget($this->requestCacheKey);

    // Second view after cache expiration
    $response = $this->service->storeRecord($request);

    expect($response->getData(true))->toMatchArray([
        'message' => 'Chapter viewed',
        'expires_in' => 180,
    ]);

    // Two records should exist
    expect(ChapterAnalysis::count())->toBe(2);
});

