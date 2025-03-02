<?php

use App\Models\Mogou;
use App\Models\SubMogou;
use App\Repo\Admin\SubMogouRepo\SubMogouDeleteRepo;
use Database\Seeders\CategorySeeder;
use Tests\Support\TestStorage;

uses()->group('unit', 'sub-mogou-delete');
uses(TestStorage::class);

beforeEach(function(){
    $this->seed([
        CategorySeeder::class
    ]);

    $this->bootStorage();

    $this->mogou = Mogou::factory()->create();

});


it('deletes subMogou successfully', function () {
    // Create real model instances
    $subMogou = SubMogou::factory()->create(['mogou_id' => $this->mogou->id]);

    $repo = new SubMogouDeleteRepo($this->mogou, $subMogou);

    $result = $repo->delete();

    expect($result)->toBeTrue();
});
