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

    public function getPublisherType(BotPublisher $publisher) : PublisherInterface
    {
        return match($publisher->type->value){
            SocialMediaType::Telegram->value => new TelegramBotPublisher($publisher->token_key),
            SocialMediaType::Discord->value => new DiscordBotPublisher(),
            default => throw new InvalidPublisherType($publisher->type->value . ' is not a valid publisher type')
        };
    }

    public function getInfo() : mixed
    {
        return $this->linedPublisher->getInfo();
    }



}
