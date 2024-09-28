<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthRequestThrottle
{
    protected string $key;

    public function __construct(protected string $email, protected string $ip)
    {
        $this->key = $this->generateThrottleKey();
    }

    /**
     * Ensure that the request is not rate-limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->key, $this->maxAttempts())) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->key);

        throw ValidationException::withMessages(
            [
            'message' => trans(
                'auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
                ]
            ),
            ]
        );
    }

    /**
     * Increment the number of attempts for the throttle key.
     */
    public function hit(): void
    {
        RateLimiter::hit($this->key);
    }

    /**
     * Clear the throttle key attempts.
     */
    public function clear(): void
    {
        RateLimiter::clear($this->key);
    }

    /**
     * Get the maximum number of attempts allowed.
     */
    protected function maxAttempts(): int
    {
        return 5; // You can make this configurable if needed.
    }

    /**
     * Generate a unique throttle key for the request.
     */
    protected function generateThrottleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . $this->ip);
    }
}
