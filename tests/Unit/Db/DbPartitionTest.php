<?php

use App\Services\Partition\TablePartition;
use Illuminate\Database\Eloquent\Model;

// Group the test
uses()->group('unit', 'dbTablePartition');

beforeEach(function(){
    TablePartition::setLockedRotation(2);

    $this->model = new class extends Model {

        use App\Traits\DbPartition;

        protected string $baseTable = 'sub_mogous';

        protected string $partition_prefix = 'sub_mogous';
    };
});


test("check given partition table exists with checkTablePartition in db",function(){
    $dbPartition = $this->model;

    $this->assertFalse($dbPartition->checkTablePartition('unknown_table'));

});


test("create alpha partition table if not exists",function(){

    $dbPartition = $this->model;


    $dbPartition->createPartition();

    $this->assertTrue($dbPartition->checkTablePartition('alpha_sub_mogous'));

});

test("create beta partition table cuz alpha already exists",function(){

    $dbPartition = $this->model;


    $dbPartition->createPartition(); // creating alpha partition table
    $dbPartition->createPartition();  // creating beta partition table

    $this->assertTrue($dbPartition->checkTablePartition('beta_sub_mogous'));

});

test("prevent creating partition table over locked",function(){

    $dbPartition = $this->model;


    $dbPartition->createPartition(); // creating alpha partition table
    $dbPartition->createPartition();  // creating beta partition table
    $dbPartition->createPartition();  // creating gamma partition table

    $this->assertFalse($dbPartition->checkTablePartition('gamma_sub_mogous'));
});

test("increase the locked partition table to 3",function(){

    $dbPartition = $this->model;


    $dbPartition->createPartition(); // creating alpha partition table
    $dbPartition->createPartition();  // creating beta partition table

    TablePartition::setLockedRotation(3);

    $dbPartition->createPartition();  // creating gamma partition table

    $this->assertTrue($dbPartition->checkTablePartition('gamma_sub_mogous'));
});


test("Model tables are match with locked count of tables in trait with dbConstructing",function(){
    $dbPartition = $this->model;

    $dbPartition->dbConstructing();

    $available_tables = TablePartition::availableRotationKey();

    foreach ($available_tables as $table) {
        $this->assertTrue($dbPartition->checkTablePartition($table."_sub_mogous"));
    }

});

test("Model tables don't with locked count of tables in trait without dbConstructing",function(){
    $dbPartition = $this->model;

    TablePartition::setLockedRotation(5);

    $available_tables = TablePartition::availableRotationKey();

    foreach ($available_tables as $table) {
        $this->assertFalse($dbPartition->checkTablePartition($table."_sub_mogous"));
    }

});
