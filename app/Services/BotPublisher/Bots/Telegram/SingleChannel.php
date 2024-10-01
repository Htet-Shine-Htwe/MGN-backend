<?php

namespace App\Services\BotPublisher\Bots\Telegram;

use WeStacks\TeleBot\TeleBot;


class SingleChannel
{
    public function __construct(protected TeleBot $service_bot,protected string $channel_id)
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
