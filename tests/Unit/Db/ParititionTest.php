<?php
use App\Traits\DbPartition;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubscriptionSeeder;
use Database\Seeders\UserSeeder;
use App\Models\User;
use App\Repo\User\Favorite\UserFavoriteRepo;
use App\Services\Partition\TablePartition;

// Group the test
uses()->group('unit', 'tablePartition');


test("get the random rotation key",function(){
    $random_key = TablePartition::getRandomRotationKey();

    $this->assertContains($random_key,TablePartition::availableRotationKey());
});

// test("get the limited rotation key collection",function(){

//     $available_keys = (new TablePartition([]))->availableRotationKey();

//     $this->assertCount(2,$available_keys);
// });
