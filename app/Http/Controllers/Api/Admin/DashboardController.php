<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repo\Admin\Dashboard\ContentGrowthRepo;
use App\Repo\Admin\Dashboard\DashboardRepo;
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

        // date: 2025-03-03T07:31:22.998Z
        $date = $request->date;
        $startDate = date('Y-m-01', strtotime($date));
        $endDate = date('Y-m-t', strtotime($date));

        [$mostChapterUploadedAdmins,$chaptersByWeek,$getContentByFavorites] = Concurrency::run(
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
            ]
        );


    }
}
