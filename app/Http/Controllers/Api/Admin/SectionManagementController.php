<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\SectionManagement\SectionManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionManagementController extends Controller
{

    public function __construct(protected SectionManagementService $sms)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $baseSection = $this->sms->getBySection($request->section)->load('childSections');

        return response()->json([
            "baseSection" => $baseSection,
        ]);
    }

    public function attachNewChild(Request $request): JsonResponse
    {
        $this->sms->attachNewChild($request->section, $request->child);

        return response()->json([
            'message' => 'section updated successfully',
        ]);
    }

    public function removeChild(Request $request): JsonResponse
    {
        $this->sms->removeChild($request->section, $request->child);

        return response()->json( [
            'message' => 'section updated successfully',
        ]);
    }


}
