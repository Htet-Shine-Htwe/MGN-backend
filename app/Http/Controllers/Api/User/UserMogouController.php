<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Mogou;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserMogouController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $mogou = $request->mogou;

        $mogou = Mogou::where('slug', $mogou)->with('categories')->firstOrFail();


        $mogou->append('total_view_count');

        $subMogous = $mogou->subMogous($mogou->rotation_key)
            ->select('id', 'title', 'slug', 'chapter_number', 'created_at')
            ->latest('chapter_number')->limit(5)->get();

        $isFavorite = auth('sanctum')->user()?->favorites()->where('mogou_id', $mogou->id)->exists();

        return response()->json(
            [
                'mogou' => $mogou,
                'is_favorite' => $isFavorite,
                'chapters' => $subMogous,
            ]
        );
    }

    public function relatedPostPerMogou(Request $request): JsonResponse
    {
        $mogou = $request->mogou;

        $mogou = Mogou::where('slug', $mogou)->firstOrFail();

        $relatedMogous = Mogou::select('id', 'title', 'rotation_key', 'slug', 'author', 'cover', 'total_chapters')
            ->where('id', '!=', $mogou->id)
            ->whereHas(
                'categories',
                function ($query) use ($mogou) {
                    $query->whereIn('category_id', $mogou->categories->pluck('id'));
                }
            )

            ->latest()->limit(6)->get();

        return response()->json(
            [
                'mogous' => $relatedMogous
            ]
        );
    }
}
