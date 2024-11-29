<?php

namespace App\Services\BotPublisher;

use App\Enum\SocialMediaType;
use App\Models\BotPublisher;
use App\Services\BotPublisher\Publisher\LinedPublisher;
use App\Services\BotPublisher\Publisher\SocialPublisher;
use Illuminate\Database\Eloquent\Collection;

class GetBotServices
{

    public function __construct(){

    }

    /**
     * getBotPublishers
     *
     * @return Collection<int, BotPublisher>
     */
    public function getBotPublishers(string $type) : Collection
    {

        $labelType = SocialMediaType::getByLabel($type);

        $botPublishers = BotPublisher::where('type', $labelType)->with('socialChannels')->get();

        return $botPublishers;
    }

    public function getBotPublisher(int $id) : BotPublisher
    {
        $botPublisher = BotPublisher::where('id', $id)->first();

        $SocialProviderChannels = (new SocialPublisher($botPublisher->token_key, $botPublisher->type->value))->get()->getChannelsWithSubscribers();

        $botPublisher->channels = $SocialProviderChannels;

        return $botPublisher;
    }

    public function getBot(int $id) : LinedPublisher
    {
        $botPublisher = BotPublisher::where('id', $id)->first();
        $linePublisher = (new SocialPublisher($botPublisher->token_key, $botPublisher->type->value))->get();

        return $linePublisher;
    }

}
