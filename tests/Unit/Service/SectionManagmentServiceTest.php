<?php

use App\Enum\MogousStatus;
use App\Models\BaseSection;
use App\Models\ChildSection;
use App\Models\Mogou;
use App\Services\SectionManagement\SectionManagementService;

uses()->group('unit','sms-test');

it('retrieves a section by its type', function () {
    $mockSection = BaseSection::factory()->create([
        'section_name' => 'popular',
        'section_description' => 'This is a popular section',
        'component_limit' => 10
    ]);

    mock(BaseSection::class)
        ->shouldReceive('where')
        ->with('section_name', 'popular')
        ->andReturnSelf()
        ->shouldReceive('firstOrFail')
        ->andReturn($mockSection);

    $service = new SectionManagementService();

    $section = $service->getBySection('popular');

    expect($section)->toBeInstanceOf(BaseSection::class);
});

it('retrieves mogou sections with visibility and selection', function () {
    $mockSection = BaseSection::factory()->create([
        'section_name' => 'popular',
        'section_description' => 'This is a popular section',
        'component_limit' => 10
    ]);

    $mockMogou = Mogou::factory()->count(3)->create([
        'status' => MogousStatus::PUBLISHED->value,
    ]);
    ChildSection::factory()->count(1)->create([
        'pivot_key' => 1,
        'base_section_id' => 1
    ]);

    mock(BaseSection::class)
    ->shouldReceive('where')
    ->with('section_name', 'popular')
    ->andReturnSelf()
    ->shouldReceive('firstOrFail')
    ->andReturn($mockSection);

    mock(Mogou::class)
        ->shouldReceive('select')
        ->andReturnSelf()
        ->shouldReceive('whereIn')
        ->andReturnSelf()
        ->shouldReceive('with')
        ->andReturnSelf()
        ->shouldReceive('get')
        ->andReturn($mockMogou);

        $service = new SectionManagementService();
        $mogouSection = $service->getMogouSection('popular');


        expect($mogouSection)->toBeArray();
        expect($mogouSection[0])->toHaveKeys(['id', 'is_selected', 'is_visible']);
        expect($mogouSection[0]['is_selected'])->toBeTrue();

});



it('throws an exception when exceeding component limit', function () {
    $mockSection = BaseSection::factory()->create(['section_name'=>'popular','component_limit' => 2]);
    $mockChildSections = ChildSection::factory()->count(3)->create(['pivot_key' => 1,'base_section_id' => 1]); // Simulate 3 children already added

    mock(BaseSection::class)
        ->shouldReceive('where')
        ->with('section_name', 'popular')
        ->andReturnSelf()
        ->shouldReceive('firstOrFail')
        ->andReturn($mockSection);

    $mockSection->childSections = collect($mockChildSections);

    $service = new SectionManagementService();

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("You can't add more than 2 components to this section");

    $service->attachNewChild('popular', 'child-key');
});

it('removes a child section by pivot key', function () {
    $mockSection = BaseSection::factory()->create([
        'section_name' => 'popular',
        'component_limit' => 2
    ]);

    Mogou::factory()->count(3)->create([
        'status' => MogousStatus::PUBLISHED->value,
    ]);

    ChildSection::factory()->create([
        'pivot_key' => 'child-key', // Use an actual pivot_key for removal
        'base_section_id' => $mockSection->id,
    ]);

    $service = new SectionManagementService();

    $section = $service->removeChild('popular', 'child-key');

    expect($section)->toBeInstanceOf(BaseSection::class);

    expect(ChildSection::where('pivot_key', 'child-key')->exists())->toBeFalse();
});

it('searches for mogous and sets is_selected based on existing child sections', function () {
    $mockSection = BaseSection::factory()->create([
        'section_name' => 'popular',
        'component_limit' => 2
    ]);

    $mockMogous =Mogou::factory()->count(1)->create([
        "title" => "Mogou Title",
        'status' => MogousStatus::PUBLISHED->value,
    ]);

    ChildSection::factory()->create([
        'pivot_key' => 1, // Use an actual pivot_key for removal
        'base_section_id' => $mockSection->id,
    ]);

    mock(BaseSection::class)
        ->shouldReceive('where')
        ->with('section_name', 'popular')
        ->andReturnSelf()
        ->shouldReceive('firstOrFail')
        ->andReturn($mockSection);

    mock(Mogou::class)
        ->shouldReceive('select')
        ->andReturnSelf()
        ->shouldReceive('where')
        ->andReturnSelf()
        ->shouldReceive('take')
        ->andReturnSelf()
        ->shouldReceive('get')
        ->andReturn($mockMogous);

    $service = new SectionManagementService();

    $result = $service->searchMogou("mogou", 'popular');

    expect($result[0])->toHaveKey('is_selected', true);
});
