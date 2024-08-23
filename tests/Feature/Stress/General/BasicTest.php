<?php

use function Pest\Stressless\stress;

it('has a fast response time', function () {
    // $result = stress('127.0.0.1:8000');s
    $host = env('APP_URL');

    $result = stress($host)->concurrency(5)->for(5)->seconds()->dump();

    expect($result->requests()->duration()->med())->toBeLessThan(100); // < 100.00ms
})->group('stress-test');
