<?php

namespace App\Services\Subscription;

use App\Models\Subscription;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionAnalysis
{
    public function getCurrentMonthProfit(): float
    {
        return (float) Subscription::join('user_subscriptions', 'subscriptions.id', '=', 'user_subscriptions.subscription_id')
            ->whereBetween('user_subscriptions.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('subscriptions.price');
    }

    public function getPreviousMonthProfit(): float
    {
        return (float) Subscription::join('user_subscriptions', 'subscriptions.id', '=', 'user_subscriptions.subscription_id')
            ->whereBetween('user_subscriptions.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
            ->sum('subscriptions.price');
    }


    public function getPreviousMonthSubscriptions(): int
    {
        return UserSubscription::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
    }

    public function getCurrentMonthSubscriptions(): int
    {
        return UserSubscription::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
    }


    public function getCurrentMonthSubscriptionPopularity(): array
    {
        $subscriptions = Subscription::all();
        $popularity = [];

        foreach ($subscriptions as $subscription) {
            $popularity[$subscription->title] = UserSubscription::where('subscription_id', $subscription->id)
                ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->count();
        }
        return $popularity;
    }

    public function getPreviousMonthSubscriptionPopularity(): array
    {
        $subscriptions = Subscription::all();
        $popularity = [];

        foreach ($subscriptions as $subscription) {
            $popularity[$subscription->title] = UserSubscription::where('subscription_id', $subscription->id)
                ->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                ->count();
        }
        return $popularity;
    }

    public function getSubscriptionComparisonAllTime(): array
    {
        // want subscription_name => count
        $subscriptions = Subscription::all();
        $subscriptionCount = [];
        foreach ($subscriptions as $subscription) {
            $subscriptionCount[$subscription->title] = UserSubscription::where('subscription_id', $subscription->id)->count();
        }

        return $subscriptionCount;
    }


    public function analysis(): array
    {

        return [
            'previous_month' => [
                'total_users' => $this->getPreviousMonthSubscriptions(),
                'total_profit' => $this->getPreviousMonthProfit(),
                'packages' => $this->getPreviousMonthSubscriptionPopularity()
            ],
            'current_month' => [
                'total_users' => $this->getCurrentMonthSubscriptions(),
                'total_profit' => $this->getCurrentMonthProfit(),
                'packages' => $this->getCurrentMonthSubscriptionPopularity()
            ],
            'get_popularity' => $this->getSubscriptionComparisonAllTime(),
        ];
    }
}
