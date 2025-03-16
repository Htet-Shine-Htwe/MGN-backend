<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repo\Admin\Dashboard\ContentGrowthRepo;
use App\Repo\Admin\Dashboard\DashboardRepo;
use App\Repo\Admin\Dashboard\RevenueGrowthRepo;
use App\Repo\Admin\Dashboard\UserDashboardRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function __construct(protected DashboardRepo $dashboardRepo,protected UserDashboardRepo $userDashboardRepo)
    {
    }

    public function stats() : JsonResponse
    {

        [$subscriptions,$users,$traffics,$contents] = Concurrency::run(
            [
                fn() => (new DashboardRepo)->subscriptions(),
                fn() => (new DashboardRepo)->users(),
                fn() => (new DashboardRepo)->traffic(),
                fn() => (new DashboardRepo)->contentUploaded(),
            ]
        );

        return response()->json(
            [
                'subscriptions' => $subscriptions,
                'users' => $users,
                'traffics' => $traffics,
                'contents' => $contents,
            ]
        );
    }

    public function userGrowthStats() : JsonResponse
    {
       [$userByLocations,$userRegistrationByMonths,$userLoginThisWeek,$isUserTrafficSummary] = Concurrency::run(
            [
                fn() => (new UserDashboardRepo)->userByLocations(),
                fn() => (new UserDashboardRepo)->userRegistrationByMonths(),
                fn() => (new UserDashboardRepo)->userLoginThisWeek(),
                fn() => (new UserDashboardRepo)->isUserTrafficSummary(),
            ]
        );

        return response()->json(
            [
                'user_chart' => $userByLocations,
                'registration_chart' => $userRegistrationByMonths,
                'login_chart' => $userLoginThisWeek,
                'user_traffics' => $isUserTrafficSummary,
            ]
        );
    }

    public function chapterGrowthStats(Request $request) : JsonResponse
    {
        $date = $request->date;
        $startDate = date('Y-m-01', strtotime($date));
        $endDate = date('Y-m-t', strtotime($date));

        [$mostChapterUploadedAdmins,$chaptersByWeek,$getContentByFavorites,$mostViewContents] = Concurrency::run(
            [
                fn() => (new ContentGrowthRepo($startDate,$endDate))->mostChapterUploadedAdmins(),
                fn() => (new ContentGrowthRepo($startDate,$endDate))->chapterUploadedBetweenTimePeriod(),
                fn() => (new ContentGrowthRepo($startDate,$endDate))->getContentByFavorites(),
                fn() => (new ContentGrowthRepo($startDate,$endDate))->getMostViewedContents(),
            ]
        );

        return response()->json(
            [
                'most_chapter_uploaded_admins' => $mostChapterUploadedAdmins,
                'chapters_by_week' => $chaptersByWeek,
                'content_by_favorites' => $getContentByFavorites,
                'most_view_contents' => $mostViewContents,

            ]
        );
    }

    public function revenueGrowthStats(Request $request) : JsonResponse
    {
        $date = $request->date;
        $startDate = date('Y-m-01', strtotime($date));
        $endDate = date('Y-m-t', strtotime($date));

        [$countBySubscriptions,$monthlySubscriptions,$revenueByDaysOfTheMonth] = Concurrency::run(
            [
                fn() => (new RevenueGrowthRepo($startDate,$endDate))->getCountBySubscriptions(),
                fn() => (new RevenueGrowthRepo($startDate,$endDate))->getMonthlySubscriptions(),
                fn() => (new RevenueGrowthRepo($startDate,$endDate))->getRevenueByDaysOfTheMonth(),
            ]
        );

        return response()->json(
            [
                'count_by_subscriptions' => $countBySubscriptions,
                'monthly_subscriptions' => $monthlySubscriptions,
                'revenue_by_days_of_the_month' => $revenueByDaysOfTheMonth,
            ]
        );
    }

    public function dailyStats() : JsonResponse
    {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day', strtotime($today)));
        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($today)));
        [$subscriptions,$users,$traffics,$revenue,$trafficByChapters] = Concurrency::run(
            [
                fn() => (new DashboardRepo)->setDates($today,$tomorrow,$yesterday,$today)->subscriptions(),
                fn() => (new DashboardRepo)->setDates($today,$tomorrow,$yesterday,$today)->users(),
                fn() => (new DashboardRepo)->setDates($today,$tomorrow,$yesterday,$today)->traffic(),
                fn() => (new DashboardRepo)->setDates($today,$tomorrow,$yesterday,$today)->revenue(),
                fn() => (new DashboardRepo)->setDates($today,$tomorrow,$yesterday,$today)->trafficByChapters(),
            ]
        );

        $folder = "/";

        return response()->json(
            [
                'subscriptions' => $subscriptions,
                'users' => $users,
                'traffics' => $traffics,
                'revenue' => $revenue,
                'traffic_by_chapters' => $trafficByChapters,
                'up_time' => fGetUptime(),
                'time_zone' => date_default_timezone_get(),
                'disk_space' => formatBytes(disk_total_space($folder)),
                'disk_space_used' => formatBytes(disk_total_space($folder) - disk_free_space($folder)),
                'disk_used_percentage' => ((disk_total_space($folder) - disk_free_space($folder)) / disk_total_space($folder)) * 100,
            ]
        );
    }
}
