<?php

namespace App\Services\BotPublisher;
use App\Models\BotPublisher;

class CreateBot
{
    /**
     * create
     *
     * @param array<string, mixed> $body
     * @return BotPublisher
     */
    public static function create(array $body) : BotPublisher
    {
        return BotPublisher::create($body);
    }
}
