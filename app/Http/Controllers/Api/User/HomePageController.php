<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\MogousStatus;
use App\Enum\SocialInfoType;
use App\Http\Controllers\Controller;
use App\Models\Mogou;
use App\Models\SocialInfo;
use App\Repo\Admin\Mogou\MogouRepo;
use App\Services\SectionManagement\SectionManagementService;
use App\Traits\CacheResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomePageController extends Controller
{

    use CacheResponse;

    public array $tagKeys = ['homepage'];
    // make constructor
    public function __construct(protected MogouRepo $mogouRepo, protected SectionManagementService $sms) {}

    public function carousel(): JsonResponse
    {
        $cacheKey = config("control.cache_key.homepage.carousel");

        $mogous = $this->cacheResponse($cacheKey, 300, function () {
            $mogous_ids = $this->sms->getBySection("hero_highlight_slider")->childSections
                ->where("is_visible", 1)
                ->pluck('pivot_key');

            return Mogou::select("id", "title", "slug", "cover", "rotation_key", "description", "finish_status", 'mogou_type', 'status', "rating")
                ->where('status', MogousStatus::PUBLISHED->value)
                ->with('categories:title')
                ->whereIn('id', $mogous_ids)
                ->get();
        });

        return response()->json(
            [
                'mogous' => $mogous
            ]
        );
    }

    public function recommended(): JsonResponse
    {
        $cacheKey = config("control.cache_key.homepage.recommend");

        $mogous = $this->cacheResponse($cacheKey, 300, function () {
            $mogous_ids = $this->sms->getBySection("main_page_recommended")->childSections
                ->where("is_visible", 1)
                ->pluck('pivot_key');

            return Mogou::select("id", "title", "slug", "cover", "rotation_key", "description", "finish_status", 'mogou_type', 'status', "rating")
                ->where('status', MogousStatus::PUBLISHED->value)
                ->with('categories:title')
                ->whereIn('id', $mogous_ids)
                ->take(20)
                ->get();
        });

        return response()->json(
            [
                'mogous' => $mogous
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

                $subMogou = $mogou->subMogous($key)->select(
                    'id',
                    'title',
                    'slug',
                    'chapter_number',
                    'created_at',
                    'subscription_only',
                    'third_party_url',
                    'third_party_redirect'
                )->latest('chapter_number')->limit(2)->get();

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
