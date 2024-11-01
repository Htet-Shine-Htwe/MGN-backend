<?php

namespace App\Models;

use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAvatar extends Model
{
    use HasFactory, HydraMedia;

    public function getAvatarPathAttribute(string $value): string
    {
        return $this->getMedia($value,'public/user_avatars');
    }

}
