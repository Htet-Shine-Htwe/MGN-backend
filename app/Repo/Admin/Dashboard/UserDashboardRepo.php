<?php

namespace App\Repo\Admin\Dashboard;

use App\Models\ChapterAnalysis;
use App\Models\LoginHistory;
use App\Models\User;

class UserDashboardRepo
{
    public function userRegistrationByMonths() : object
    {
        return User::whereNotNull('created_at') // Ensure created_at is not NULL
        ->where('created_at', '>=', now()->subMonths(6)) // Only last six months
        ->selectRaw('TO_CHAR(created_at, \'Month\') as key, COUNT(id) as count') // Format in SQL
        ->groupBy('key')
        ->get();
    }

    public function userByLocations() : object
    {
        $top_five = LoginHistory::query()
            ->select('country as key')
            ->selectRaw('count(user_id) as count')
            ->groupBy('country')
            ->limit(3)
            ->get();

        $other = LoginHistory::query()
            ->selectRaw('\'Other\' as key')
            ->selectRaw('count(user_id) as count')
            ->whereNotIn('country', $top_five->pluck('key')->toArray())
            ->first();

        return $top_five->push($other);

    }

    public function userLoginThisWeek() : object
    {
        return LoginHistory::query()
        ->selectRaw('TO_CHAR(login_at, \'Day\') as key, COUNT(id) as count, EXTRACT(ISODOW FROM login_at) as weekday')
        ->whereBetween('login_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->groupBy('key', 'weekday')
        ->orderBy('weekday')
        ->get();
    }

    public function isUserTrafficSummary() : array
    {
        // get user traffic count key and count where user_id is not null this month
        // date 30days ago
        // { date: "2024-06-01", user: 178, non: 200 },
        $traffic = ChapterAnalysis::query()
            ->selectRaw('TO_CHAR(date, \'YYYY-MM-DD\') as key, COUNT(id) as count, CASE WHEN user_id IS NULL THEN \'non_user\' ELSE \'user\' END as type')
            ->whereBetween('date', [now()->subDays(15), now()])
            ->groupBy('key', 'type')
            ->orderBy('key')
            ->get()
            ->groupBy('type');

        $userTraffic = $traffic->get('user', collect());
        $nonUserTraffic = $traffic->get('non_user', collect());

        $userMap = $userTraffic->pluck('count', 'key')->all();
        $nonUserMap = $nonUserTraffic->pluck('count', 'key')->all();

        // Generate all dates in the 30-day range
        $startDate = now()->subDays(14)->startOfDay();
        $endDate = now()->startOfDay();
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

        $formattedTraffic = [];
        foreach ($period as $date) {
            $dateKey = $date->format('Y-m-d');
            $formattedTraffic[] = [
                'date' => $dateKey,
                'user' => $userMap[$dateKey] ?? 0,
                'non_user' => $nonUserMap[$dateKey] ?? 0,
            ];
        }

        return $formattedTraffic;

    }
}
