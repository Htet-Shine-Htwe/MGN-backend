<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFavorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'mogou_id'];

    /**
     * user
     *
     * @return BelongsTo<User, UserFavorite>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * mogou
     *
     * @return BelongsTo<Mogou, UserFavorite>
     */
    public function mogou(): BelongsTo
    {
        return $this->belongsTo(Mogou::class);
    }
}
