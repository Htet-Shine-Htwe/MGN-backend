<?php

use App\Services\Auth\AuthRequestThrottle;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;


uses()->group('unit','auth-throttle');

it('does not throw an exception if the request is not rate-limited', function () {
    $email = 'test@example.com';
    $ip = '127.0.0.1';

    RateLimiter::shouldReceive('tooManyAttempts')
        ->once()
        ->with('test@example.com|127.0.0.1', 5)
        ->andReturn(false);

    $throttle = new AuthRequestThrottle($email, $ip);

    expect(fn() => $throttle->ensureIsNotRateLimited())->not->toThrow(ValidationException::class);
});

it('throws a validation exception if the request is rate-limited', function () {
    $email = 'test@example.com';
    $ip = '127.0.0.1';

    RateLimiter::shouldReceive('tooManyAttempts')
        ->once()
        ->with('test@example.com|127.0.0.1', 5)
        ->andReturn(true);

    RateLimiter::shouldReceive('availableIn')
        ->once()
        ->with('test@example.com|127.0.0.1')
        ->andReturn(60);

    $throttle = new AuthRequestThrottle($email, $ip);

    expect(fn() => $throttle->ensureIsNotRateLimited())->toThrow(ValidationException::class);
});

it('increments the number of attempts for the throttle key', function () {
    $email = 'test@example.com';
    $ip = '127.0.0.1';

    RateLimiter::shouldReceive('hit')
        ->once()
        ->with('test@example.com|127.0.0.1');

    $throttle = new AuthRequestThrottle($email, $ip);

    $throttle->hit();

    RateLimiter::shouldHaveReceived('hit')->with('test@example.com|127.0.0.1');
});

it('clears the throttle key attempts', function () {
    $email = 'test@example.com';
    $ip = '127.0.0.1';

    RateLimiter::shouldReceive('clear')
        ->once()
        ->with('test@example.com|127.0.0.1');

    $throttle = new AuthRequestThrottle($email, $ip);

    $throttle->clear();

    RateLimiter::shouldHaveReceived('clear')->with('test@example.com|127.0.0.1');
});
