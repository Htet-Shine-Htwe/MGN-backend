<?php

namespace App\Models;

use App\Enum\SocialInfoStatus;
use App\Enum\SocialInfoType;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialInfo extends Model
{
    use HasFactory,HydraMedia;

    protected $fillable = [
        'name',
        'type',
        'icon',
        'meta',
        'cover_photo',
        "text_url",
        'redirect_url',
        'active'
    ];

    protected $appends = [
        'cover_photo_url'
    ];

    protected $casts = [
        'type' => SocialInfoType::class,
        'active' => SocialInfoStatus::class
    ];

    public function getCoverPhotoUrlAttribute()
    {
        return $this->getMedia($this->cover_photo,"public/social_info");
    }
}
