<?php

namespace App\Services\BotPublisher\Bots\Telegram;

class SingleChannel
{
    public function __construct(protected $service_bot,protected string $channel_id)
    {

    }

    public function getTotalMembers() : mixed
    {
        return $this->service_bot->getChatMemberCount(
            [
            'chat_id' => $this->channel_id
            ]
        );
    }


}
