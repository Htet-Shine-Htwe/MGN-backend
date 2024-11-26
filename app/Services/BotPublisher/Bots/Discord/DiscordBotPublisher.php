<?php
namespace App\Services\BotPublisher\Bots\Discord;


use App\Contracts\PublisherInterface;

class DiscordBotPublisher implements PublisherInterface
{
    public function getPublisherDetail() : string
    {
        return 'Telegram';
    }

    public function checkIsExistOnProvider(string $id) : bool
    {
        return $id == 1;
    }
}
