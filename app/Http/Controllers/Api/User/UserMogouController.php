<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Mogou;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserMogouController extends Controller
{
    /**
     * Display the specified Mogou.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $mogou = Mogou::where('slug', $request->mogou)
            ->with('categories')
            ->firstOrFail()
            ->append('total_view_count');

        $subMogous = $mogou->subMogous($mogou->rotation_key)
            ->select('id', 'title', 'slug', 'chapter_number', 'created_at', 'subscription_only', 'third_party_url', 'third_party_redirect')
            ->latest('chapter_number')
            ->limit(10)
            ->get();

        $isFavorite = (auth('sanctum')->user() instanceof \App\Models\User) && auth('sanctum')->user()?->favorites()->where('mogou_id', $mogou->id)->exists();

        return response()->json([
            'mogou' => $mogou,
            'is_favorite' => $isFavorite,
            'chapters' => $subMogous,
        ]);
    }

    /**
     * Get more chapters of the specified Mogou.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getMoreChapters(Request $request): JsonResponse
    {
        $mogou = Mogou::where('slug', $request->mogou)->firstOrFail();

        $subMogous = $mogou->subMogous($mogou->rotation_key)
            ->select('id', 'title', 'slug', 'chapter_number', 'created_at', 'subscription_only', 'third_party_url', 'third_party_redirect')
            ->latest('chapter_number')
            ->get();

        return response()->json(['chapters' => $subMogous]);
    }

    /**
     * Get related posts for the specified Mogou.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function relatedPostPerMogou(Request $request): JsonResponse
    {
        $mogou = Mogou::where('slug', $request->mogou)->firstOrFail();

        $relatedMogous = Mogou::select('id', 'title', 'rotation_key', 'slug', 'author', 'cover', 'total_chapters')
            ->where('id', '!=', $mogou->id)
            ->whereHas('categories', function ($query) use ($mogou) {
                $query->whereIn('category_id', $mogou->categories->pluck('id'));
            })
            ->latest()
            ->limit(6)
            ->get();

        return response()->json(['mogous' => $relatedMogous]);
    }
}
