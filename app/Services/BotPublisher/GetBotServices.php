<?php

namespace App\Services\BotPublisher;

use App\Enum\SocialMediaType;
use App\Models\BotPublisher;
use App\Services\BotPublisher\Publisher\SocialPublisher;
use Illuminate\Database\Eloquent\Collection;

class GetBotServices
{

    public function __construct(protected readonly string $type){

    }

    /**
     * getBotPublishers
     *
     * @return Collection<int, BotPublisher>
     */
    public function getBotPublishers() : Collection
    {

        $labelType = SocialMediaType::getByLabel($this->type);

        $botPublishers = BotPublisher::where('type', $labelType)->with('socialChannels')->get();

        return $botPublishers;
    }


}
