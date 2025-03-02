<?php

namespace App\Models;

use Database\Factories\BotSocialChannelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotSocialChannel extends Model
{
    /** @use HasFactory<BotSocialChannelFactory> */

    use HasFactory;

    protected $fillable = [
        'bot_publisher_id',
        'social_channel_id',
    ];
}
