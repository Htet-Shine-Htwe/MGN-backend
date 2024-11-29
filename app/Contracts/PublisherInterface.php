<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PublisherInterface
{
    /**
     * get the detail of the publisher
     * @return mixed
     */
    public function getPublisherDetail() : mixed;

    /**
     * status check of the bot id on related social provider
     *
     * @param string $id
     * @return bool
     */
    public function checkIsExistOnProvider(string $id) : bool;


    /**
     * get the channels with subscribers
     *
     * @param  Collection $channels
     * @return mixed
     */
    public function getChannelsWithSubscribers(Collection $channels) : mixed;

    /**
     * check the channel exist on provider with bot id
     *
     * @param  string $id
     * @param  string $channel_id
     * @return mixed
     */
    public function checkChannelExistOnProvider(string $id,string $channel_token_key) : mixed;

}
