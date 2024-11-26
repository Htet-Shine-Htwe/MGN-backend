<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotSocialChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_publisher_id',
        'social_channel_id',
    ];
}
