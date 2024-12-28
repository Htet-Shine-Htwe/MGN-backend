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
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
        'subscription_end_date',
        'last_login_at',
        'active',
        'background_color',
        'avatar_id',
    ];

    // appends
    protected $appends = ['subscription_name', 'avatar_url'];

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
        return 'id';
    }


    public function getSubscriptionEndDateAttribute(string $value): string|null
    {
        $timestamp = strtotime($value);
        return $timestamp !== false ? date('Y-m-d H:i:s', $timestamp) : null;
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
    }

    /*
    * Relationships
    */

    /**
     * subscription
     *
     * @return BelongsTo<Subscription, User>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'current_subscription_id', 'id');
    }

    /**
     * subscriptions
     *
     * @return HasMany<UserSubscription>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * favorites
     *
     * @return HasMany<UserFavorite>
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * login_history
     *
     * @return HasMany<LoginHistory>
     */
    public function loginHistory(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * avatar
     *
     * @return BelongsTo<UserAvatar, User>
     */
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(UserAvatar::class);
    }

    /**
     * scopeSearch
     *
     * @param  Builder<static> $query
     * @return Builder<static>
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when(
            $search,
            function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            }
        );
    }

    public function getAvatarUrlAttribute(): string | null
    {
        return $this->avatar?->avatar_url_path;
    }

    /**
     * scopeFilter
     *
     * @param  Builder<static> $query
     * @return Builder<static>
     */
    public function scopeFilterSubscription(Builder $query): Builder
    {
        $filter = request()->input('subscriptions');

        return $query->when($filter, function ($query) use ($filter) {
            if (is_string($filter) && strpos($filter, ',') !== false) {
                $status = explode(',', $filter);
                return $query->whereIn('current_subscription_id', $status);
            } else {
                return $query->where('current_subscription_id', $filter);
            }
        });
    }

    /**
     * scopeExpiredSubscription
     *
     * @param  Builder<static> $query
     * @param  string $expired
     * @return Builder<static>
     */
    public function scopeExpiredSubscription(Builder $query, ?string $expired): Builder
    {
        return $query->when(
            $expired,
            function ($query) {
                return $query->where('subscription_end_date', '<', now());
            }
        );
    }

    /**
     * scopeFilterActiveUser
     *
     * @param  Builder<static> $query
     * @param  string $active
     * @return Builder<static>
     */
    public function scopeFilterActiveUser(Builder $query, ?string $active): Builder
    {

        return $query->when(isset($active), function ($query) use ($active) {
            return $query->where('active', $active);
        });
    }

    public function getSubscriptionNameAttribute(): string | null
    {
        return $this->subscription?->title;
    }
}
