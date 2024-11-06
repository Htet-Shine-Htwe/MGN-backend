<?php

namespace App\Models;

use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAvatar extends Model
{
    use HasFactory, HydraMedia;

    protected $fillable = [
        'avatar_name',
        'avatar_path',
    ];

    protected $appends = ['avatar_url_path'];

    public function getAvatarUrlPathAttribute(): string
    {
        return $this->getMedia($this->avatar_path,'public/user_avatars');
    }



}
