<?php

namespace App\Services\BotPublisher\Publisher;

use App\Contracts\PublisherInterface;
use App\Enum\SocialMediaType;
use App\Exceptions\InvalidPublisherType;
use App\Models\BotPublisher;
use App\Services\BotPublisher\Bots\Discord\DiscordBotPublisher;
use App\Services\BotPublisher\Bots\Telegram\TelegramBotPublisher;

class SocialPublisher
{
    protected LinedPublisher $linedPublisher;

    public function __construct(protected string $token_key,protected int $type)
    {
        $publisherType = $this->getPublisherType($token_key,$type);
        $botPublisher = BotPublisher::where('token_key', $token_key)->where('type', $type)->first();
        $this->linedPublisher = new LinedPublisher($publisherType,$botPublisher);
    }

    public function getPublisherType(string $token_key,int $type): PublisherInterface
    {
        return match ($type) {
            SocialMediaType::Telegram->value => new TelegramBotPublisher($token_key),
            SocialMediaType::Discord->value => new DiscordBotPublisher(),
            default => throw new InvalidPublisherType("Invalid Publisher Type"),
        };
    }


    public function get() : LinedPublisher
    {
        return $this->linedPublisher;
    }

}
