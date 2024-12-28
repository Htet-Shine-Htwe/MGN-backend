<?php

namespace App\Repo\Admin\SocialChannel;

use App\Enum\SocialMediaType;
use App\Http\Requests\SocialChannelActionRequest;
use App\Models\BotSocialChannel;
use App\Models\SocialChannel;
use App\Services\BotPublisher\GetBotServices;
use Illuminate\Support\Facades\DB;

class SocialChannelActionRepo
{
    public function __construct()
    {
    }

    public function create(SocialChannelActionRequest $request): mixed
    {
        // Create a new social channel

        $bot = (new GetBotServices())->getBot((int) $request->bot_id);

        $valid_channel = $bot->checkChannelExistOnProvider($request->token_key);

        $channel = SocialChannel::where('token_key', $request->token_key)->first();

        if ($channel) {
            $publisher_channel = BotSocialChannel::where('bot_publisher_id', $request->bot_id)->where('social_channel_id', $channel->id)->first();

            if ($publisher_channel) {
                throw new \Exception('Channel already exists', 400);
            }
        }

        if ($valid_channel) {
            DB::transaction(function () use ($request, $valid_channel, $channel) {
                $channel = SocialChannel::firstOrCreate([
                    'token_key' => $request->token_key,
                    'type' => SocialMediaType::getByLabel($request->bot_type)
                ], [
                    'name' => $valid_channel->title,
                    'meta_data' => $valid_channel,
                ]);

                BotSocialChannel::create([
                    'bot_publisher_id' => $request->bot_id,
                    'social_channel_id' => $channel->id,
                ]);
            });

            return $channel;
        }

        else{;
            throw new \Exception('Failed to bind a channel', 400);
        }

    }
}
