<?php

namespace App\Models;

use Database\Factories\BotPublisherPostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotPublisherPost extends Model
{
    /** @use HasFactory<BotPublisherPostFactory> */

    use HasFactory;

    protected $fillable = [
        'bot_publisher_id',
        'mogou_id',
        'sub_mogou_id',
        'social_channel_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * botPublisher
     *
     * @return BelongsTo<BotPublisher,$this>
     */
    public function botPublisher(): BelongsTo
    {
        return $this->belongsTo(BotPublisher::class);
    }

}
