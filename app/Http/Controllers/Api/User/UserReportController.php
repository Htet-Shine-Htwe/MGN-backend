<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportActionRequest;
use App\Repo\Admin\Report\CreateReportRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    public function __construct(
        protected CreateReportRepo $createReportRepo
    ) {
    }

    public function create(ReportActionRequest $request): JsonResponse
    {
        $report = $this->createReportRepo->create($request);
        return response()->json(
            [
            'message' => 'Report created successfully',
            'data' => $report
            ]
        );
    }
}


