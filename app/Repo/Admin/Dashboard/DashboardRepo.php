<?php

namespace App\Repo\Admin\Dashboard;

use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTime;

class DashboardRepo
{
    protected string $thisMonthStartDate;
    protected string $thisMonthEndDate;
    protected string $lastMonthStartDate;
    protected string $lastMonthEndDate;

    public function __construct()
    {
        $this->thisMonthStartDate = Carbon::now()->startOfMonth()->toDateString();
        $this->thisMonthEndDate = Carbon::now()->endOfMonth()->toDateString();
        $this->lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $this->lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();
    }

    public function popularChapterByWeek() : void
    {
        $lastWeekStartDate = Carbon::now()->subWeek()->startOfWeek()->toDateString();
        $lastWeekEndDate = Carbon::now()->subWeek()->endOfWeek()->toDateString();

        // Add logic to fetch popular chapters within the last week
    }

    public function subscriptions() : array
    {
        return $this->getMonthlySummary(new UserSubscription);
    }

    public function users() : array
    {
        return $this->getMonthlySummary(new User);
    }

    protected function getMonthlySummary(Model $model) : array
    {
        $thisMonthCount = $this->getSummary($model, $this->thisMonthStartDate, $this->thisMonthEndDate);
        $lastMonthCount = $this->getSummary($model, $this->lastMonthStartDate, $this->lastMonthEndDate);

        $diffInPercentage = $this->calculatePercentageDifference($thisMonthCount, $lastMonthCount);
        $status = $diffInPercentage > 0 ? 'success' : 'destructive';

        return [
            'this_month' => $thisMonthCount,
            'last_month' => $lastMonthCount,
            'status' => $status,
            'percentage' => $diffInPercentage
        ];
    }

    protected function getSummary(Model $model,string $startDate,string $endDate) : int
    {
        return $model::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    protected function calculatePercentageDifference(int $current,int $previous) : int|float|string
    {
        if ($previous == 0) {
            return 0;
        }

        return number_format((($current - $previous) / $previous) * 100, 2);
    }
}
