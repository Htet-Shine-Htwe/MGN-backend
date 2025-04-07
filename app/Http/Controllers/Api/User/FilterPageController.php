<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Repo\Admin\Mogou\MogouRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FilterPageController extends Controller
{
    public function __construct(protected MogouRepo $mogouRepo) {}

    public function index(Request $request): JsonResponse
    {
        $collection =  $this->mogouRepo
            ->withCategories()
            ->withFilterGenres()
            ->withLegalOnly()
            ->get($request);

        $collection->each(
            function ($mogou) {
                $key = $mogou->rotation_key;

                $subMogou = $mogou->subMogous($key)->select(
                    'id',
                    'title',
                    'slug',
                    'description',
                    'chapter_number',
                    'created_at',
                    'subscription_only',
                    'third_party_url',
                    'third_party_redirect'
                )->latest('chapter_number')->limit(3)->get();

                $mogou->setRelation('subMogous', $subMogou);
            }
        );


        return response()->json(
            [
                'mogous' => $collection
            ]
        );
    }
}
