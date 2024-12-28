<?php

namespace App\Services\BotPublisher\Publisher;

use App\Contracts\PublisherInterface;
use App\Models\BotPublisher;
use App\Models\SocialChannel;

class LinedPublisher
{
    public function __construct(protected PublisherInterface $publisher,protected ?BotPublisher $botPublisher)
    {

    }

    public function getInfo() : mixed
    {
        return $this->publisher->getPublisherDetail();
    }

    public function checkIsExistOnProvider(string $id): bool
    {
        return $this->publisher->checkIsExistOnProvider($id);
    }

    public function getChannelsWithSubscribers() : mixed
    {

        $socialChannelIds = $this->botPublisher->socialChannels->pluck('id');

        $channels = SocialChannel::whereIn('id', $socialChannelIds)->get();

        if($channels->isEmpty()){
            return [];
        }

        return $this->publisher->getChannelsWithSubscribers($channels);
    }

    public function checkChannelExistOnProvider(string $channel_token_key): mixed
    {
        return $this->publisher->checkChannelExistOnProvider($this->botPublisher->id,$channel_token_key);
    }


}
