<?php

namespace App\Events;

use App\Models\BotPublisher;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BotActivity
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected BotPublisher $botPublisher)
    {
        //
    }

    /**
     * Handle the event.
     */

    public function handle(): void
    {
        $this->botPublisher->update([
            'last_activity' => now(),
        ]);
    }


}
