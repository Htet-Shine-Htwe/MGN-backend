<?php

use App\Models\Mogou;
use App\Models\SubMogou;
use App\Repo\Admin\SubMogouRepo\SubMogouDeleteRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;

uses()->group('unit', 'sub-mogou-delete');

it('deletes subMogou successfully', function () {
    // Create real model instances
    $mogou = Mogou::factory()->create();
    $subMogou = SubMogou::factory()->create(['mogou_id' => $mogou->id]);

    $repo = new SubMogouDeleteRepo($mogou, $subMogou);

    $result = $repo->delete();

    expect($result)->toBeTrue();
});
