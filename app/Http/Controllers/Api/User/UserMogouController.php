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
            ->select('id', 'title', 'slug', 'chapter_number', 'created_at','subscription_only','third_party_url','third_party_redirect')
            ->latest('chapter_number')->limit(10)->get();

        $is_auth = auth('sanctum')->user();

        // if is auth is User  Model instance
        if($is_auth instanceof \App\Models\User){
            $isFavorite = $is_auth ? $is_auth->favorites()->where('mogou_id', $mogou->id)->exists() : null;
        }

        return response()->json(
            [
                'mogou' => $mogou,
                'is_favorite' => $isFavorite ?? false,
                'chapters' => $subMogous,
            ]
        );
    }

    public function getMoreChapters(Request $request): JsonResponse
    {
        $mogou = $request->mogou;

        $mogou = Mogou::where('slug', $mogou)->firstOrFail();

        $subMogous = $mogou->subMogous($mogou->rotation_key)
            ->select('id', 'title', 'slug', 'chapter_number', 'created_at','subscription_only','third_party_url','third_party_redirect')
            ->latest('chapter_number')->get();

        return response()->json(
            [
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
