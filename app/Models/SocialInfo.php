<?php

namespace App\Models;

use App\Enum\SocialInfoType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'icon',
        'cover_photo',
        'url'
    ];

    protected $casts = [
        'type' => SocialInfoType::class,
    ];

    public function getCoverPhotoAttribute($value)
    {
        return $value ? asset('storage/social_info/banners/' . $value) : null;
    }
}
