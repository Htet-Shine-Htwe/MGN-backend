<?php

namespace App\Services\BotPublisher\Publisher;

use App\Contracts\PublisherInterface;
use App\Enum\SocialMediaType;
use App\Exceptions\InvalidPublisherType;
use App\Models\BotPublisher;
use App\Services\BotPublisher\Bots\Discord\DiscordBotPublisher;
use App\Services\BotPublisher\Bots\Telegram\TelegramBotPublisher;

final class SocialPublisher
{
    protected LinedPublisher $linedPublisher;

    public function __construct(protected BotPublisher $publisher)
    {
        $publisherType = $this->getPublisherType($publisher);

        $this->linedPublisher = new LinedPublisher($publisherType);
    }

    public function getPublisherType(BotPublisher $publisher): PublisherInterface
    {
        return match ($publisher->type) {
            SocialMediaType::Telegram => new TelegramBotPublisher($publisher->token_key),
            SocialMediaType::Discord => new DiscordBotPublisher()
        };
    }


    public function getInfo() : mixed
    {
        return $this->linedPublisher->getInfo();
    }



}
