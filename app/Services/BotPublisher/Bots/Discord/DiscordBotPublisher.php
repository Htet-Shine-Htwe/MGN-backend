<?php
namespace App\Services\BotPublisher\Bots\Discord;


use App\Contracts\PublisherInterface;

class DiscordBotPublisher implements PublisherInterface
{
    public function getPublisherDetail() : string
    {
        return 'Telegram';
    }
}
