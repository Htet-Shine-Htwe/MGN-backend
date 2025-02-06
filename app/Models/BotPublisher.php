<?php

namespace App\Models;

use App\Enum\SocialMediaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property mixed $channels
 */
class BotPublisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token_key',
        'type',
        'is_active',
        'last_activity'
    ];

    protected $casts = [
        'type' => SocialMediaType::class,
    ];

    protected $appends = ['bot_type'];

    public function getBotTypeAttribute(): string
    {
        return SocialMediaType::getKey($this->type);
    }

    public function getCreatedAtAttribute(string $value): string
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }


    /**
     * socialChannels
     *
     * @return BelongsToMany<SocialChannel>
     */
    public function socialChannels(): BelongsToMany
    {
        return $this->belongsToMany(
            SocialChannel::class,
            'bot_social_channels',
            'bot_publisher_id',
            'social_channel_id'
        );
    }
}
