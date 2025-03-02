<?php

namespace App\Repo\Admin\Dashboard;

use App\Models\ChapterAnalysis;
use App\Models\Mogou;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function subscriptions() : array
    {
        return $this->getMonthlySummary(new UserSubscription);
    }

    public function users() : array
    {
        return $this->getMonthlySummary(new User);
    }

    public function contentUploaded() : array
    {
        return $this->getMonthlySummary(new Mogou);
    }

    public function traffic() :array
    {
        $tomorrow = Carbon::now()->addDay()->toDateString();
        $today = Carbon::now()->toDateString();
        $yesterday = Carbon::now()->subDay()->toDateString();
        $todayTraffic = $this->getSummary(new ChapterAnalysis, $today, $tomorrow,'date');
        $yesterdayTraffic = $this->getSummary(new ChapterAnalysis, $yesterday, $today,'date');

        $diffInPercentage = $this->calculatePercentageDifference($todayTraffic, $yesterdayTraffic);

        return [
            'current' => $todayTraffic,
            'prev' => $yesterdayTraffic,
            'status' => $diffInPercentage > 0 ? 'success' : 'destructive',
            'percentage' => $diffInPercentage
        ];
    }

    protected function getMonthlySummary(Model $model) : array
    {
        $thisMonthCount = $this->getSummary($model, $this->thisMonthStartDate, $this->thisMonthEndDate);
        $lastMonthCount = $this->getSummary($model, $this->lastMonthStartDate, $this->lastMonthEndDate);

        $diffInPercentage = $this->calculatePercentageDifference($thisMonthCount, $lastMonthCount);
        $status = $diffInPercentage > 0 ? 'success' : 'destructive';

        return [
            'current' => $thisMonthCount,
            'prev' => $lastMonthCount,
            'status' => $status,
            'percentage' => $diffInPercentage
        ];
    }

    protected function getSummary(Model $model,string $startDate,string $endDate,string $key = "created_at") : int
    {
        return $model::whereBetween($key, [$startDate, $endDate])->count();
    }

    protected function calculatePercentageDifference(int $current,int $previous) : int|float|string
    {
        if ($previous == 0) {
            return 0;
        }

        return number_format((($current - $previous) / $previous) * 100, 2);
    }
}
