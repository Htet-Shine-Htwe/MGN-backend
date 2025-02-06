<?php

use App\Services\Partition\TablePartition;

// Group the test
uses()->group('unit', 'tablePartition');


test("get the random rotation key",function(){
    $random_key = TablePartition::getRandomRotationKey();

    $this->assertContains($random_key,TablePartition::availableRotationKey());
});


