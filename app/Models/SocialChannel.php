<?php

namespace App\Models;

use App\Enum\SocialMediaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token_key',
        'type',
        'is_active',
    ];

    protected $casts = [
        'type' => SocialMediaType::class,
    ];

    protected $appends = ['bot_type'];

    public function getBotTypeAttribute(): string
    {
        return SocialMediaType::getKey($this->type);
    }
}
