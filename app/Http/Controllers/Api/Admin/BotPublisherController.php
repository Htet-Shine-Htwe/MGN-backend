<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBotPublisherRequest;
use App\Http\Requests\UpdateBotPublisherRequest;
use App\Models\BotPublisher;

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



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBotPublisherRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BotPublisher $botPublisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BotPublisher $botPublisher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
