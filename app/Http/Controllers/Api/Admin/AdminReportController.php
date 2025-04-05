<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repo\Admin\Report\ReportIndexRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function __construct(protected ReportIndexRepo $rir) {}


    public function index(Request $request): JsonResponse
    {
        $reports = $this->rir->index($request);
        return response()->json(
            [
                'reports' => $reports,
            ]
        );
    }

    public function show(string $id): JsonResponse
    {
        $report = $this->rir->show($id);
        return response()->json(
            [
                'report' => $report
            ]
        );
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $this->rir->updateStatus($request, $id);
        return response()->json(
            [
                'message' => 'Report status updated successfully',
                'id' => $id,
                'status' => $request->status
            ]
        );
    }
}
