<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'mogou_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mogou()
    {
        return $this->belongsTo(Mogou::class);
    }
}
