<?php

namespace App\Models;

use App\Enum\SocialMediaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $providers
 */
class SocialChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token_key',
        'type',
        'is_active',
        'meta_data',
    ];

    protected $casts = [
        'type' => SocialMediaType::class,
    ];

    protected $appends = ['bot_type'];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->meta_data = json_encode($model->meta_data);
        });

        static::updating(function ($model) {
            $model->meta_data = json_encode($model->meta_data);
        });
    }

    public function getBotTypeAttribute(): string
    {
        return SocialMediaType::getKey($this->type);
    }

    public function getMetaDataAttribute(?string $value): array | null
    {
        if (!$value) {
            return null;
        }
        return json_decode($value, true);
    }
}
