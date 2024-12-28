<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    /**
     * scopeSearch
     *
     * @param  Builder<Subscription> $query
     * @param  string|null $search
     * @return Builder<Subscription>
     */
    public function scopeSearch($query, $search): Builder
    {
        return $query->where('title', 'like', '%' . $search . '%');
    }

    /**
     * scopeCountBy
     *
     * @param  Builder<Subscription> $query
     * @param  string|null $countBy
     * @return Builder<Subscription>
     */
    public function scopeCountBy($query, $countBy): Builder
    {
        return $query->when(
            $countBy, function ($q) use ($countBy) {
                return $q->orderBy('users_count', $countBy);
            }
        );
    }

    /**
     * scopePriceBy
     *
     * @param Builder<Subscription> $query
     * @param string|null $price
     * @return Builder<Subscription>
     */
    public function scopePriceBy($query, $price): Builder
    {
        return $query->when(
            $price, function ($q) use ($price) {
                return $q->orderBy('price', $price);
            }
        );
    }

    /**
     * users
     *
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'current_subscription_id');
    }
}
