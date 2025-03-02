<?php

namespace App\Models;

use Database\Factories\UserFavoriteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFavorite extends Model
{
    /** @use HasFactory<UserFavoriteFactory> */
    use HasFactory;
    protected $fillable = ['user_id', 'mogou_id'];

    /**
     * user
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * mogou
     *
     * @return BelongsTo<Mogou, $this>
     */
    public function mogou(): BelongsTo
    {
        return $this->belongsTo(Mogou::class);
    }
}
