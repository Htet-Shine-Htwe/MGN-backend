<?php

namespace App\Services\BotPublisher\Bots\Telegram;

use App\Contracts\PublisherInterface;
use WeStacks\TeleBot\TeleBot;

class TelegramBotPublisher implements PublisherInterface
{
    protected TeleBot $serviceBot;
    public function __construct(protected string $api_key)
    {
        $this->serviceBot = new TeleBot($api_key);
    }

    public static function provider(string $api_key): TelegramBotPublisher
    {
        return new self($api_key);
    }

    public function getPublisherDetail(): mixed
    {
        $botDetails = $this->individualChannel('-1002198423534')->getTotalMembers();

        return json_encode($botDetails);
    }


    public function individualChannel(string $channel_id): SingleChannel
    {
        return new SingleChannel($this->serviceBot, $channel_id);
    }

    public function checkIsExistOnProvider(string $id): bool
    {
        $url = "https://api.telegram.org/bot$id/getMe";
        $response = new \GuzzleHttp\Client(
            [
                'base_uri' => $url,
                'http_errors' => false,
                'headers' => [
                    'accept' => 'application/json',
                ]
            ]
        );

        $response = $response->get($url);
        if ($response->getStatusCode() == 200) {
            return true;
        }
        return false;
    }
}
