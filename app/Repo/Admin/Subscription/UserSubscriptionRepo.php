<?php
namespace App\Repo\Admin\Subscription;


use App\Models\User;

class UserSubscriptionRepo
{

    public User $user;

    public function setUser(User|string $user) : UserSubscriptionRepo
    {
        if(is_string($user)) {
            $this->user = User::where('user_code', $user)->firstOrFail();
        }
        else{
            $this->user = $user;
        }
        return $this;
    }

    public function subscriptions(): mixed
    {
        return collect($this->user->subscriptions->map(
            function ($subscription) {
                return [
                'id' => $subscription->id,
                'title' => $subscription->subscription->title,
                'price' => $subscription->subscription->price,
                'created_at' => $subscription->created_at->format('Y-m-d'),
                ];
            }
        )->sortByDesc('created_at')->values());
    }

}
