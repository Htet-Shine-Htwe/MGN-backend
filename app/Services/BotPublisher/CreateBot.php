<?php

namespace App\Services\BotPublisher;
use App\Models\BotPublisher;

class CreateBot
{
    public static function create(mixed $body) : BotPublisher
    {
        return BotPublisher::create($body);
    }
}
