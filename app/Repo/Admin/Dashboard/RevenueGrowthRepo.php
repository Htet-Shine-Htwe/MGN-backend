<?php

namespace App\Repo\Admin\Dashboard;

use App\Models\UserSubscription;
use App\Services\ApplicationConfig\CacheApplicationConfigService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueGrowthRepo
{
    public function __construct(protected string $startDate, protected string $endDate) {}

    public function getMonthlySubscriptions(): array
    {
        $applicationConfig = (new CacheApplicationConfigService())->getApplicationConfig();
        return [
            'target' => $applicationConfig->target_subscriptions,
            'subscriptions' => UserSubscription::whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function getCountBySubscriptions(): array
    {
        return UserSubscription::join('subscriptions', 'subscriptions.id', '=', 'user_subscriptions.subscription_id')
            ->whereBetween('user_subscriptions.created_at', [$this->startDate, $this->endDate])
            ->select('subscriptions.title', DB::raw("count(*) as total"))
            ->groupBy('subscriptions.title') // Use subscriptions.name instead of subscription_id
            ->get()
            ->toArray();
    }

    public function getRevenueByDaysOfTheMonth(): array
    {
        // Step 1: Generate all dates in the range
        $allDates = [];
        $current = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        while ($current->lte($end)) {
            $allDates[$current->toDateString()] = 0; // Default revenue = 0
            $current->addDay();
        }

        // Step 2: Query actual revenue from subscriptions
        $revenues = UserSubscription::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->join('subscriptions', 'subscriptions.id', '=', 'user_subscriptions.subscription_id')
            ->select(
                DB::raw('DATE(user_subscriptions.created_at) as date'),
                DB::raw('SUM(subscriptions.price) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue', 'date') // Fetch as associative array [date => revenue]
            ->toArray();

        // Step 3: Merge actual revenue into the full date range
        foreach ($revenues as $date => $revenue) {
            $allDates[$date] = $revenue; // Replace 0 with actual revenue
        }

        // Step 4: Convert to array of objects for easy usage in charts
        return collect($allDates)->map(fn($revenue, $date) => [
            'date' => $date,
            'revenue' => $revenue,
            'random' => $revenue + rand(1, 100), // Add random data for testing
        ])->values()->toArray();
    }
}
