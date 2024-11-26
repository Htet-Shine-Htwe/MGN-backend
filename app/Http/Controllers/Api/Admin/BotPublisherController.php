<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBotPublisherRequest;
use App\Models\BotPublisher;
use App\Services\BotPublisher\CreateBot;
use App\Services\BotPublisher\GetBotServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BotPublisherController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $bots = (new GetBotServices($request->type))->getBotPublishers();
        return response()->json(
            [
            'success' => true,
            'bots' => $bots
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
            withException:true
        );
    }
}
