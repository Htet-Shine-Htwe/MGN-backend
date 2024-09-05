<?php

namespace App\Models;

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
        'cover_photo',
        'url',
        'meta'
    ];

    protected $casts = [
        'type' => SocialInfoType::class,
    ];

    public function getCoverPhotoAttribute($value)
    {
        return $this->getMedia($value,"public/social_info");
    }
}
