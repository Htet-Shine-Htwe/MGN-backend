<?php

namespace App\Services\BotPublisher\Bots\Telegram;

use App\Contracts\PublisherInterface;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use WeStacks\TeleBot\TeleBot;

class TelegramBotPublisher implements PublisherInterface
{
    protected TeleBot $serviceBot;
    protected Client $httpClient;

    public function __construct(protected string $api_key)
    {
        $this->serviceBot = new TeleBot($api_key);
        $this->httpClient = $this->createHttpClient();
    }

    public function self(): mixed{
        return $this->serviceBot;
    }

    public static function provider(string $api_key): self
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
        $response = $this->makeTelegramRequest("bot{$id}/getMe");
        return $response->getStatusCode() === 200;
    }

    public function getChannelsWithSubscribers(Collection $channels): mixed
    {
        return $channels->map(function ($channel) {
            $channel->providers = $this->individualChannel($channel->token_key)->getChatInfo();
            return $channel;
        });
    }

    public function checkChannelExistOnProvider(int $id, string $channel_token_key): mixed
    {
        return  $this->individualChannel($channel_token_key)->getChatDetail();
    }

    protected function makeTelegramRequest(string $endpoint, array $queryParams = []): \Psr\Http\Message\ResponseInterface
    {
        $url = "https://api.telegram.org/{$endpoint}";
        return $this->httpClient->get($url, ['query' => $queryParams]);
    }

    protected function createHttpClient(): Client
    {
        return new Client([
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }
}
