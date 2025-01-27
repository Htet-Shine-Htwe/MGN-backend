<?php

namespace App\Repo\User\Favorite;

use App\Models\Mogou;
use App\Models\User;
use App\Models\UserFavorite;
use Illuminate\Database\Eloquent\Collection;

class UserFavoriteRepo
{

    public ?User $user;

    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    public function setUser(User $user): UserFavoriteRepo
    {
        $this->user = $user;
        return $this;
    }


    public function addFavorite(int $mogou_id): bool
    {
        if ($this->isFavorite($mogou_id)) {
            return false;
        }

        UserFavorite::create(
            [
            'user_id' => $this->user?->id,
            'mogou_id' => $mogou_id
            ]
        );

        return true;
    }

    public function removeFavorite(int $mogou_id): bool
    {
        try{
            $this->user?->favorites()->where('mogou_id', $mogou_id)->delete();
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }

    /**
     * getFavorites
     *
     * @return array<mixed>
     */
    public function getFavorites(): array
    {
        return $this->user?->favorites()->pluck('mogou_id')->toArray() ?? [];
    }

    /**
     * getFavoriteMogous
     *
     * @return Collection<int, Mogou>
     */
    public function getFavoriteMogous(): Collection
    {
        return Mogou::select('id','title','slug','cover')
        ->whereIn('id', $this->getFavorites())->get();
    }

    public function isFavorite(int $mogou_id): bool
    {
        return $this->user?->favorites()->where('mogou_id', $mogou_id)->exists() ? true : false;
    }

}
