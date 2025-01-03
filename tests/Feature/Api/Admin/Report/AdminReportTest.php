<?php

use App\Enum\ReportStatusEnum;
use App\Models\Report;
use Database\Seeders\ReportSeeder;
use Tests\Support\UserAuthenticated;

uses()->group('admin','api','report-management');
uses( UserAuthenticated::class);

beforeEach(function(){
    $this->setupAdmin();
    $this->seed([
        ReportSeeder::class,
    ]);
});


it("admin can get list of reports",function()
{
    $response = $this->authenticatedAdmin()->getJson(route('api.admin.reports.index'));

    $response->assertOk();
    $response->assertJsonStructure([
        'reports',
    ]);

    $counts = $response->json()['reports']['total'];

    $this->assertEquals(10, $counts);
});

it("admin can close the opened report",function(){
    $test_report = Report::factory()->create([
        'status' => ReportStatusEnum::OPEN->value,
    ]);

    $response = $this->authenticatedAdmin()->postJson(route('api.admin.reports.updateStatus',[
        'report'=> $test_report->id
    ]),[
        'status' => ReportStatusEnum::RESOLVED->value,
    ]);

    $response->assertOk();

    // find that report in db with closed status
    $this->assertDatabaseHas('reports', [
       'id'=> $test_report->id,
       'status'=> ReportStatusEnum::RESOLVED->value,
       ]);
});


