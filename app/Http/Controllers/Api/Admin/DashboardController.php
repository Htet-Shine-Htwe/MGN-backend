<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
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

    public function userStats() : JsonResponse
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
                'userByLocations' => $userByLocations,
                'userRegistrationByMonths' => $userRegistrationByMonths,
                'userLoginThisWeek' => $userLoginThisWeek,
                'isUserTrafficSummary' => $isUserTrafficSummary,
            ]
        );
    }

    public function userLocation() : JsonResponse
    {
        $data = $this->userDashboardRepo->userByLocations();

        return response()->json($data);
    }

    public function userRegistrationByMonths() : JsonResponse
    {
        $data = $this->userDashboardRepo->userRegistrationByMonths();

        return response()->json($data);
    }

    public function userLoginThisWeek() : JsonResponse
    {
        $data = $this->userDashboardRepo->userLoginThisWeek();

        return response()->json($data);
    }

    public function userTrafficSummary() : JsonResponse
    {
        $data = $this->userDashboardRepo->isUserTrafficSummary();

        return response()->json($data);
    }
}
