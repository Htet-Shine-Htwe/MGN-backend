<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repo\Admin\Dashboard\DashboardRepo;
use App\Repo\Admin\Dashboard\UserDashboardRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function __construct(protected DashboardRepo $dashboardRepo,protected UserDashboardRepo $userDashboardRepo)
    {
    }

    public function stats() : JsonResponse
    {
        return response()->json(
            [
                'subscriptions' => $this->dashboardRepo->subscriptions(),
                'users' => $this->dashboardRepo->users(),
                'traffics' => $this->dashboardRepo->traffic(),
                'contents' => $this->dashboardRepo->contentUploaded(),
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
