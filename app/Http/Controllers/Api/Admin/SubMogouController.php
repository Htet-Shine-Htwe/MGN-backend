<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repo\Admin\SubMogouRepo\SubMogouActionRepo;
use Illuminate\Http\Request;

class SubMogouController extends Controller
{
    public function __construct(protected SubMogouActionRepo $subMogouActionRepo)
    {
    }

    public function saveNewDraft(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'chapter_number' => 'required|integer',
            'mogou_id' => 'required|integer|exists:mogous,id',
        ]);

        $mogou = $this->subMogouActionRepo->saveNewDraft($data);

        return  response()->json([
            'sub_mogou' => $mogou
        ], 201);
    }

    public function updateCover(Request $request)
    {
        $data = $request->validate([
            'cover' => 'required|image',
            'mogou_id' => 'required|integer|exists:mogous,id',
            'id' => 'required|integer',
            'slug' => 'required|string'
        ]);

        $mogou = $this->subMogouActionRepo->updateCover($data);

        return  response()->json([
            'sub_mogou' => $mogou
        ], 200);
    }

    public function show($mogous_id, $sub_mogou_id)
    {
        $subMogou = $this->subMogouActionRepo->show($mogous_id, $sub_mogou_id);

        return response()->json([
            'sub_mogou' => $subMogou
        ], 200);
    }


}
