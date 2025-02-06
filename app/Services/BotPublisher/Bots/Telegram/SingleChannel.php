<?php

namespace App\Services\BotPublisher\Bots\Telegram;

use WeStacks\TeleBot\TeleBot;


class SingleChannel
{
    public function __construct(protected TeleBot $service_bot,protected string $channel_id)
    {

    }

    public function getChatInfo() : mixed
    {
        $chat = $this->getChatDetail();

        return [
            'id' => $chat?->id,
            'title' => $chat?->title,
            'type' => $chat?->type,
            "description"=> $chat->description ?? "",
            "invite_link"=> $chat->invite_link ??"",
            'total_members' => $this->getTotalMembers()
        ];
    }

    public function getChatDetail() : mixed
    {
        return $this->service_bot->getChat(
            [
            'chat_id' => $this->channel_id
            ]
        );
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
