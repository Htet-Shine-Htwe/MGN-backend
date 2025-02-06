<?php

namespace App\Repo\Admin\Dashboard;

use App\Models\LoginHistory;
use App\Models\User;

class UserDashboardRepo
{
    public function userRegistrationByMonths() : object
    {
        return User::whereNotNull('created_at') // Ensure created_at is not NULL
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
}
