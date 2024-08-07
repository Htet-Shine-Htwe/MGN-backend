<?php

use App\Http\Controllers\Api\Utils\TypeGeneratorController;
use Illuminate\Http\Request;

uses()->group('unit', 'type-generator-test');


test('generateName method successfully generates random name', function () {
    $typeGeneratorController = new TypeGeneratorController();

    $response = $typeGeneratorController->generateName(new Request());

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData()->random_user_name)->not()->toBeNull();
});

test("regenerateName method successfully regenerates random name", function () {
    $typeGeneratorController = new TypeGeneratorController();

    $response = $typeGeneratorController->generateName(new Request());

    $random_user_name = $response->getData()->random_user_name;

    $response = $typeGeneratorController->generateName(new Request());

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData()->random_user_name)->not()->toBe($random_user_name);
});
