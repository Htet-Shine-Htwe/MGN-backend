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
        $baseSection = $this->sms->getMogouSection($request->section);

        return response()->json([
            "baseSection" => $baseSection,
        ]);
    }

    public function attachNewChild(Request $request): JsonResponse
    {
        $this->sms->attachNewChild($request->section, $request->child);

        return response()->json([
            'message' => 'new component was added',
        ]);
    }

    public function removeChild(Request $request): JsonResponse
    {
        $this->sms->removeChild($request->section, $request->child);

        return response()->json( [
            'message' => 'component was removed successfully',
        ]);
    }

    public function searchMogou(Request $request): JsonResponse
    {
        $mogous = $this->sms->searchMogou($request->search,$request->type);

        return response()->json([
            'mogous' => $mogous,
        ]);
    }

    public function setVisibility(Request $request): JsonResponse
    {

        $this->sms->setToggleVisibility($request->section, $request->child, $request->visibility);

        $visibilityStatus = $request->visibility ? 'visible' : 'invisible';

        return response()->json([
            'message' => 'component was activated to ' . $visibilityStatus,
        ]);

    }


}
