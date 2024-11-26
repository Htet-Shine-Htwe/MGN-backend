<?php

namespace App\Services\BotPublisher\Publisher;

use App\Contracts\PublisherInterface;

class LinedPublisher
{
    public function __construct(protected PublisherInterface $publisher)
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
}
