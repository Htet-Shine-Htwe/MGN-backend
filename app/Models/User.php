<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_code',
        'current_subscription_id',
        'subscription_end_date' ,
        'last_login_at'
    ];

    // appends
    protected $appends = ['subscription_name'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'subscription'
    ];

    public function getRouteKeyName(): string
    {
        return 'user_code';
    }


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot(): void
    {
        parent::boot();

        // static::creating(
        //     function ($user) {
        //         // with current time and unique id
        //         $user->user_code = time() . uniqid();
        //     }
        // );
    }

    /*
    * Relationships
    */

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'current_subscription_id', 'id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * scopeSearch
     *
     * @param  Builder<static> $query
     * @param  mixed $search
     * @return Builder<static>
     */
    public function scopeSearch(Builder $query,string $search) : Builder
    {
        return $query->when(
            $search, function ($query,$search) {
                return $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            }
        );
    }

     /**
     * scopeFilter
     *
     * @param  Builder<static> $query
     * @param mixed $filter
     * @return Builder<static>
     */
    public function scopeFilter(Builder $query,mixed $filter) : Builder
    {
        return $query->when(
            $filter, function ($query,$filter) {
                return $query->where('current_subscription_id', $filter);
            }
        );
    }

    /**
     * scopeExpiredSubscription
     *
     * @param  Builder<static> $query
     * @param  mixed $expired
     * @return Builder<static>
     */
    public function scopeExpiredSubscription(Builder $query,mixed $expired) : Builder
    {
        return $query->when(
            $expired, function ($query) use ($expired) {

                return $query->where('subscription_end_date', '<', now());
            }
        );
    }

    public function getSubscriptionNameAttribute() : string | null
    {
        return $this->subscription?->title;
    }
}
