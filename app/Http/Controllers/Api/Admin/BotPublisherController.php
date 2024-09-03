<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBotPublisherRequest;
use App\Http\Requests\UpdateBotPublisherRequest;
use App\Models\BotPublisher;
use App\Services\BotPublisher\CreateBot;

class BotPublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bots = BotPublisher::all();
        return response()->json([
            'success' => true,
            'data' => $bots
        ]);
    }


    public function store(StoreBotPublisherRequest $request)
    {
        return tryCatch(function() use ($request){
            $bot = CreateBot::create($request->validated());

            return response()->json([
                'message' => "Bot was created Successfully",
                'bot' => $bot
            ]);
        },
        "Failed to generate new bot"
    );
    }

    /**
     * Display the specified resource.
     */
    public function show(BotPublisher $botPublisher)
    {
        //
    }


    /**
     */
    public function update(UpdateBotPublisherRequest $request, BotPublisher $botPublisher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BotPublisher $botPublisher)
    {
        //
    }
}
