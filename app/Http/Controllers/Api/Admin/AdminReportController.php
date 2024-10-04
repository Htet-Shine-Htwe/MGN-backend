<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            [
            'message' => 'Admin report index'
            ]
        );
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(
            [
            'message' => 'Admin report show'
            ]
        );
    }

    public function updateStatus(Request $request,string $id): JsonResponse
    {
        return response()->json(
            [
            'message' => 'Admin report update status'
            ]
        );
    }

    public function delete(string $id): JsonResponse
    {
        return response()->json(
            [
            'message' => 'Admin report delete'
            ]
        );
    }
}
