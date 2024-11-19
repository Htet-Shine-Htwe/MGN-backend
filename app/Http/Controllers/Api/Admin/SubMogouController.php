<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubMogouDraftRequest;
use App\Http\Requests\SubMogouStorageUploadRequest;
use App\Http\Requests\SubMogouZipUploadRequest;
use App\Repo\Admin\SubMogouRepo\MogouPartitionFind;
use App\Repo\Admin\SubMogouRepo\SubMogouActionRepo;
use App\Repo\Admin\SubMogouRepo\SubMogouDeleteRepo;
use App\Repo\Admin\SubMogouRepo\SubMogouStorageUploadRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubMogouController extends Controller
{
    public function __construct(
        protected SubMogouActionRepo $subMogouActionRepo,
        protected SubMogouStorageUploadRepo $subMogouStorageUploadRepo
    ) {}

    public function saveNewDraft(SubMogouDraftRequest $request): JsonResponse
    {
        $mogou = $this->subMogouActionRepo->saveNewDraft($request->validated());
        return response()->json(['sub_mogou' => $mogou],201);
    }

    public function updateInfo(SubMogouDraftRequest $request): JsonResponse
    {
        $mogou = $this->subMogouActionRepo->updateInfo($request->validated());
        return response()->json(['sub_mogou' => $mogou],200);
    }

    public function updateCover(Request $request): JsonResponse
    {
        $data = $request->validate(
            [
                'cover' => 'required|image',
                'mogou_id' => 'required|integer|exists:mogous,id',
                'id' => 'required|integer',
                'slug' => 'required|string'
            ]
        );

        $mogou = $this->subMogouActionRepo->updateCover($data);

        return  response()->json(['sub_mogou' => $mogou],200);
    }

    public function show(string $mogou_slug, string $sub_mogou_id): JsonResponse
    {
        $subMogou = $this->subMogouActionRepo->show($mogou_slug, $sub_mogou_id);
        return response()->json($subMogou, 200);
    }

    public function getLatestChapterNumber(string $mogou_slug): JsonResponse
    {
        $chapterNumber = $this->subMogouActionRepo->getLatestChapterNumber($mogou_slug);
        return response()->json(['chapter_number' => $chapterNumber],200);
    }

    public function uploadStorageFiles(SubMogouStorageUploadRequest $request): JsonResponse
    {
        $this->subMogouStorageUploadRepo->upload($request);
        return response()->json(['message' => 'success'],200);
    }

    public function deleteSubMogou(Request $request): JsonResponse
    {
        $data = $request->validate(
            [
                'mogou_slug' => 'required|string|exists:mogous,slug',
                'sub_mogou_id' => 'required|integer'
            ]
        );

        $subMogou = MogouPartitionFind::getSubMogou("slug", $data['mogou_slug'])->where('id', $data['sub_mogou_id'])->firstOrFail();

        $sugMogouDelete = (new SubMogouDeleteRepo( MogouPartitionFind::$parentMogou, $subMogou))->delete();

        return response()->json(
            [
                'message' => $sugMogouDelete ? 'success' : 'failed'
            ],
            $sugMogouDelete ? 200 : 500
        );
    }
}
