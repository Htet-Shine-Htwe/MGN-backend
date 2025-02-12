<?php

namespace App\Services\ChapterAnalysis;

use App\Models\ChapterAnalysis;
use App\Models\SubMogou;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class ChapterAnalysisService
{
    private const CACHE_EXPIRATION_TIME = 60; // 2 minutes

    public function storeRecord(SubMogou $subMogou): JsonResponse
    {
        $ip = request()->ip();


        $cacheKey = $this->generateCacheKey($subMogou->mogou_id, $subMogou->id, $ip);

        if ($this->isChapterViewed($cacheKey)) {
            return $this->chapterAlreadyViewedResponse();
        }

        $subMogou->increment('views');
        $this->cacheChapterView($cacheKey);
        $this->createChapterAnalysisRecord($subMogou->id, $subMogou->mogou_id, $ip);

        return $this->chapterViewedResponse();
    }

    private function generateCacheKey(string $mogouId, string $subMogouId, string $ip): string
    {
        return "chapter_view:{$mogouId}:{$subMogouId}:{$ip}";
    }

    private function isChapterViewed(string $cacheKey): bool
    {
        return Cache::has($cacheKey);
    }

    private function cacheChapterView(string $cacheKey): void
    {
        Cache::put($cacheKey, true, self::CACHE_EXPIRATION_TIME);
    }

    private function createChapterAnalysisRecord(string $subMogouId, string $mogouId, string $ip): void
    {
        ChapterAnalysis::create([
            'sub_mogou_id' => $subMogouId,
            'mogou_id' => $mogouId,
            'ip' => $ip,
            'user_id' => auth()->id() ?? null,
        ]);
    }

    private function chapterAlreadyViewedResponse(): JsonResponse
    {
        return response()->json(['message' => 'Chapter viewed']);
    }

    private function chapterViewedResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Chapter viewed',
            'expires_in' => self::CACHE_EXPIRATION_TIME,
        ]);
    }
}
