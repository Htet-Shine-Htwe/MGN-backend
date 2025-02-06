<?php

namespace App\Services\ChapterAnalysis;

use App\Models\ChapterAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class ChapterAnalysisService
{
    private const CACHE_EXPIRATION_TIME = 180; // 3 minutes

    public function storeRecord(Request $request): JsonResponse
    {
        $ip = $request->ip();
        $subMogouId = $request->get('sub_mogou_id');
        $mogouId = $request->get('mogou_id');
        $cacheKey = $this->generateCacheKey($mogouId, $subMogouId, $ip);

        if ($this->isChapterViewed($cacheKey)) {
            return $this->chapterAlreadyViewedResponse();
        }

        $this->cacheChapterView($cacheKey);
        $this->createChapterAnalysisRecord($subMogouId, $mogouId, $ip);

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
        ]);
    }

    private function chapterAlreadyViewedResponse(): JsonResponse
    {
        return response()->json(['message' => 'Chapter already viewed']);
    }

    private function chapterViewedResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Chapter viewed',
            'expires_in' => self::CACHE_EXPIRATION_TIME,
        ]);
    }
}
