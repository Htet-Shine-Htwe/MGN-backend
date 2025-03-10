<?php

namespace App\Http\Controllers\Api\User;

use App\Events\ChapterViewed;
use App\Http\Controllers\Controller;
use App\Models\Mogou;
use App\Repo\Admin\SubMogouRepo\SubMogouImageRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $isFavorite = (auth('sanctum')->user() instanceof \App\Models\User) && auth('sanctum')->user()->favorites()->where('mogou_id', $mogou->id)->exists();

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

    public function getChapter(Request $request): JsonResponse
    {
        $mogou = Mogou::select('id','rotation_key','title','slug','cover')->where('slug', $request->mogou)->firstOrFail();

        $currentChapter = $mogou->subMogous($mogou->rotation_key)
            ->where('slug', $request->chapter)
            ->firstOrFail();

        $currentChapter['images'] = (new SubMogouImageRepo)->getImages($currentChapter,$mogou->rotation_key)->get();

        $allChapters = $mogou->subMogous($mogou->rotation_key)
            ->select("id","title","slug","chapter_number")
            ->latest('chapter_number')
            ->get() ?? null;

        $nextChapter = $mogou->subMogous($mogou->rotation_key)
            ->select("id","title","slug","chapter_number")
            ->where('chapter_number', '>', $currentChapter->chapter_number)
            ->oldest('chapter_number')
            ->first() ?? null;
        $previousChapter = $mogou->subMogous($mogou->rotation_key)
            ->select("id","title","slug","chapter_number")
            ->where('chapter_number', '<', $currentChapter->chapter_number)
            ->latest('chapter_number')
            ->first() ?? null;

        $nextChapter = $mogou->subMogous($mogou->rotation_key)
            ->select("id","title","slug","chapter_number")
            ->where('chapter_number', '>', $currentChapter->chapter_number)
            ->oldest('chapter_number')
            ->first() ?? null;

        return response()->json([
            'current_chapter' => $currentChapter,
            'prev_chapter' => $previousChapter,
            'next_chapter' => $nextChapter,
            'all_chapters' => $allChapters,
            'mogou' => $mogou,
        ]);
    }

    public function getViewed(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $mogou = Mogou::where('id', $request->mogou)->firstOrFail();
            $chapter = $mogou->subMogous($mogou->rotation_key)
                ->where('id', $request->chapter)
                ->firstOrFail();

            event(new ChapterViewed($chapter));

            DB::commit();

            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'failed', 'error' => $e->getMessage()], 500);
        }
    }
}
