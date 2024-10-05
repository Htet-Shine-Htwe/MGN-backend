<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBotPublisherRequest;
use App\Models\BotPublisher;
use App\Services\BotPublisher\CreateBot;
use Illuminate\Http\JsonResponse;

class BotPublisherController extends Controller
{

    public function index(): JsonResponse
    {
        $bots = BotPublisher::all();
        return response()->json(
            [
            'success' => true,
            'data' => $bots
            ]
        );
    }


    public function store(StoreBotPublisherRequest $request) : JsonResponse
    {
        return tryCatch(
            function () use ($request) {
                $bot = CreateBot::create($request->validated());

                return response()->json(
                    [
                    'message' => "Bot was created Successfully",
                    'bot' => $bot
                    ]
                );
            },
            "Failed to generate new bot"
        );
    }
}
