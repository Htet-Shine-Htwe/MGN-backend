<?php

namespace App\Services\BotPublisher;
use App\Models\BotPublisher;
use App\Services\BotPublisher\Publisher\SocialPublisher;

class CreateBot
{
    /**
     * create
     *
     * @param array<string, mixed> $body
     * @return BotPublisher
     */
    public static function create(array $body) : BotPublisher
    {

        $checkIfBotExists = self::checkIfBotExists($body['token_key'], $body['type']);

        if($checkIfBotExists){
            throw new \Exception("Bot already exists");
        }

        $socialPublisher = (new SocialPublisher($body['token_key'], $body['type']))->get();

        $validToCreate = $socialPublisher->checkIsExistOnProvider($body['token_key']);

        if(!$validToCreate){
            throw new \Exception("Bot is not valid ! Please check the token key");
        }

        $body['is_active'] = true;


        return BotPublisher::create($body);
    }

    public static function checkIfBotExists(string $token_key,int $type) : bool
    {
        return BotPublisher::where('token_key', $token_key)
        ->where('type', $type)
        ->exists();
    }

    public static function checkBotIsAvailable(int $id) : bool
    {
        return BotPublisher::where('id', $id)
        ->where('is_active', true)
        ->exists();
    }
}
