<?php

use App\Enum\MogouFinishStatus;
use App\Enum\MogouTypeEnum;
use App\Models\Mogou;
use App\Models\SubMogou;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogousCategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubMogouSeeder;
use Illuminate\Support\Facades\Schema;
use Tests\Support\UserAuthenticated;

uses()->group('admin','api','admin-mogou','admin-mogou-function');
uses(UserAuthenticated::class);

beforeEach(function(){

    config(['control.test.mogous_count' => 3]);

    $this->seed([
        CategorySeeder::class,
        MogouSeeder::class,
        MogousCategorySeeder::class,
        SubMogouSeeder::class
    ]);
    $this->setupAdmin();
});


test("Mogou total view is equal to its sub-mogou views",function(){
    $mogou = Mogou::first();  // Eager load the actual relationship

    $mogouTotalCount = $mogou->total_view_count;  // Get the total count

    $sub_mogous = (new SubMogou());
    $sub_mogous->partition_prefix = $mogou->rotation_key . '_sub_mogous';

    $total_sub_mogou_views = $sub_mogous->where('mogou_id',$mogou->id)->sum('views');

    $this->assertEquals($mogouTotalCount,$total_sub_mogou_views);
});

test("Mogou total view is equal to its sub-mogou views on collection",function(){
    $mogou = Mogou::get();

    $mogou->each(function($mogou){
        $mogouTotalCount = $mogou->total_view_count;  // Get the total count

        $sub_mogous = (new SubMogou());
        $sub_mogous->partition_prefix = $mogou->rotation_key . '_sub_mogous';

        $total_sub_mogou_views = $sub_mogous->where('mogou_id',$mogou->id)->sum('views');

        $this->assertEquals($mogouTotalCount,$total_sub_mogou_views);
    });
});
