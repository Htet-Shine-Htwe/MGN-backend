<?php

namespace App\Models;

use App\Enum\SocialMediaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotPublisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token_key',
        'type',
        'available_ids',
    ];

    protected $casts = [
        'available_ids' => 'array',
        'type' => SocialMediaType::class,
    ];

    protected $appends = ['bot_type'];


    public function getAvailableIdsAttribute($value)
    {
        return json_decode($value);
    }

    public function getBotTypeAttribute()
    {
        if($this->type){
            return SocialMediaType::getKey($this->type);
        }
    }
}
