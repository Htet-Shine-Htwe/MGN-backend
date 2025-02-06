<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repo\Admin\Dashboard\DashboardRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct(protected DashboardRepo $dashboardRepo)
    {
    }

    public function stats() : JsonResponse
    {
        return response()->json(
            [
                'subscriptions' => $this->dashboardRepo->subscriptions(),
                'users' => $this->dashboardRepo->users(),
            ]
        );
    }
}
