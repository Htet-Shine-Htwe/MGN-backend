<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\MogousStatus;
use App\Enum\SocialInfoType;
use App\Http\Controllers\Controller;
use App\Models\Mogou;
use App\Models\SocialInfo;
use App\Repo\Admin\Mogou\MogouRepo;
use App\Services\SectionManagement\SectionManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    // make constructor
    public function __construct(protected MogouRepo $mogouRepo,protected SectionManagementService $sms)
    {
        //
    }

    public function carousel(): JsonResponse
    {
        $mogous_ids = $this->sms->getBySection("hero_highlight_slider")->childSections->pluck('pivot_key');

        $mogou = Mogou::select("id", "title", "slug", "cover", "rotation_key", "description", "finish_status", 'mogou_type', 'status', "rating")
            ->where('status', MogousStatus::PUBLISHED->value)
            ->with('categories:title')
            ->whereIn('id', $mogous_ids)
            ->get();

        return response()->json(
            [
            'mogous' => $mogou
            ]
        );
    }

    public function mostViewed(): JsonResponse
    {
        $mogous = Mogou::select("id", "title", "slug", "cover",)
            ->where('status', MogousStatus::PUBLISHED->value)
            ->with('categories:title')
            ->take(20)
            ->get();

        return response()->json(
            [
            'mogous' => $mogous
            ]
        );
    }

    public function lastUploaded(Request $request): JsonResponse
    {
        $collection =  $this->mogouRepo
            ->withCategories()
            ->publishedOnly()
            ->get($request);

        $collection->each(
            function ($mogou) {

                $key = $mogou->rotation_key;

                $subMogou = $mogou->subMogous($key)->select('title')->latest()->limit(3)->get();

                $mogou->setRelation('subMogous', $subMogou);
            }
        );

        return response()->json(
            [
            'mogous' => $collection
            ]
        );
    }

    public function banners(): JsonResponse
    {
        $banners = SocialInfo::where('type', SocialInfoType::Banner->value)->get();

        return response()->json(
            [
            'banners' => $banners
            ]
        );
    }
}
