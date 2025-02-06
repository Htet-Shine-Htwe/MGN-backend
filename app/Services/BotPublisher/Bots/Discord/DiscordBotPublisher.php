<?php
namespace App\Services\BotPublisher\Bots\Discord;


use App\Contracts\PublisherInterface;
use Illuminate\Database\Eloquent\Collection;

class DiscordBotPublisher implements PublisherInterface
{

    public function self(): mixed
    {
        return $this;
    }
    public function getPublisherDetail() : string
    {
        return 'Discord';
    }

    public function checkIsExistOnProvider(string $id) : bool
    {
        return $id == 1;
    }

    public function getChannelsWithSubscribers(Collection $channels): mixed
    {
       return $channels->map(function($channel){
            return $channel;
        });
    }

    public function checkChannelExistOnProvider(int $id,string $channel_token_keys) : bool
    {
        return $id == 1;
    }
}
