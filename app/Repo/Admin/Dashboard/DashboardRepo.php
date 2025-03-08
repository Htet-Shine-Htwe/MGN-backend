<?php

namespace App\Repo\Admin\Dashboard;

use App\Models\ChapterAnalysis;
use App\Models\Mogou;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Repo\Admin\SubMogouRepo\MogouPartitionFind;
use App\Services\ApplicationConfig\CacheApplicationConfigService;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepo
{
    protected string $currentStartDate;
    protected string $currentEndDate;
    protected string $prevStartDate;
    protected string $prevEndDate;

    public function __construct()
    {
        $this->currentStartDate = Carbon::now()->startOfMonth()->toDateString();
        $this->currentEndDate = Carbon::now()->endOfMonth()->toDateString();
        $this->prevStartDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $this->prevEndDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();
    }

    public function setDates(string $currentStartDate, string $currentEndDate, string $prevStartDate, string $prevEndDate): self
    {
        $this->currentStartDate = $currentStartDate;
        $this->currentEndDate = $currentEndDate;
        $this->prevStartDate = $prevStartDate;
        $this->prevEndDate = $prevEndDate;

        return $this;
    }

    public function subscriptions(): array
    {
        return $this->getMonthlySummary(new UserSubscription);
    }

    public function users(): array
    {
        return $this->getMonthlySummary(new User);
    }

    public function contentUploaded(): array
    {
        return $this->getMonthlySummary(new Mogou);
    }

    public function trafficByChapters(): array
    {
        $chapters = ChapterAnalysis::query()
            ->whereBetween('date', [$this->currentStartDate, $this->currentEndDate])
            ->select(
                'sub_mogou_id',
                'mogou_id',
                DB::raw('SUM(CASE WHEN Date(date) = CURRENT_DATE THEN 1 ELSE 0 END) as today_views')
            )
            ->groupBy('sub_mogou_id', 'mogou_id')
            ->orderByDesc('today_views')
            ->limit(5)
            ->get()
            ->toArray();

        $dailyTrafficTarget = (new CacheApplicationConfigService)->getApplicationConfig()->daily_traffic_target ?? 10;

        foreach ($chapters as $key => $data) {
            $subMogou =  (new MogouPartitionFind)->getSubMogouInstance("id", $data['mogou_id'])
                ->where("id", $data['sub_mogou_id'])
                ->where("mogou_id", $data['mogou_id'])
                ->select('title', 'mogou_id')
                ->firstOrFail();

            $chapters[$key]['sub_mogou_title'] = $subMogou->title;
            $chapters[$key]['mogou_title'] = $subMogou->mogou->title;
            $chapters[$key]['percentage'] = ($data['today_views'] / $dailyTrafficTarget) * 100;
        }

        return $chapters;
    }

    public function revenue(): array
    {
        $current = $this->getRevenueSummary(new UserSubscription, $this->currentStartDate, $this->currentEndDate);
        $previous = $this->getRevenueSummary(new UserSubscription, $this->prevStartDate, $this->prevEndDate);

        $diffInPercentage = $this->calculatePercentageDifference($current, $previous);

        return [
            'current' => $current,
            'prev' => $previous,
            'status' => $diffInPercentage > 0 ? 'success' : 'destructive',
            'percentage' => $diffInPercentage
        ];
    }

    public function traffic(): array
    {
        $todayTraffic = $this->getSummary(new ChapterAnalysis, $this->currentStartDate, $this->currentEndDate, 'date');
        $yesterdayTraffic = $this->getSummary(new ChapterAnalysis, $this->prevStartDate, $this->prevEndDate, 'date');

        $diffInPercentage = $this->calculatePercentageDifference($todayTraffic, $yesterdayTraffic);

        return [
            'current' => $todayTraffic,
            'prev' => $yesterdayTraffic,
            'status' => $diffInPercentage > 0 ? 'success' : 'destructive',
            'percentage' => $diffInPercentage
        ];
    }


    protected function getMonthlySummary(Model $model): array
    {
        $thisMonthCount = $this->getSummary($model, $this->currentStartDate, $this->currentEndDate);
        $lastMonthCount = $this->getSummary($model, $this->prevStartDate, $this->prevEndDate);

        $diffInPercentage = $this->calculatePercentageDifference($thisMonthCount, $lastMonthCount);
        $status = $diffInPercentage > 0 ? 'success' : 'destructive';

        return [
            'current' => $thisMonthCount,
            'prev' => $lastMonthCount,
            'status' => $status,
            'percentage' => $diffInPercentage
        ];
    }

    protected function getSummary(Model $model, string $startDate, string $endDate, string $key = "created_at"): int
    {
        return $model::whereBetween($key, [$startDate, $endDate])->count();
    }

    protected function getRevenueSummary(Model $model, string $startDate, string $endDate): int
    {
        return $model::whereBetween('created_at', [$startDate, $endDate])
            ->with(['subscription' => function ($query) {
                $query->select('id', 'price');
            }])
            ->select('id', 'subscription_id', 'created_at')
            ->get()
            ->map(function ($subscription) {
                return $subscription->subscription->price ?? 0;
            })
            ->sum();
    }

    protected function calculatePercentageDifference(int $current, int $previous): int|float|string
    {
        if ($previous == 0) {
            return $current * 100;
        }

        return number_format((($current - $previous) / $previous) * 100, 2);
    }
}
