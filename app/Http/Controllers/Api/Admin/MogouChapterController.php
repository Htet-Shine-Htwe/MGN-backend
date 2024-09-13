<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mogou;
use App\Models\SubMogou;
use App\Repo\Admin\MogouChapter\MogouChapterRepo;
use App\Repo\Admin\SubMogouRepo\SubMogouActionRepo;
use Illuminate\Http\Request;

class MogouChapterController extends Controller
{
    public function __construct(
        protected readonly MogouChapterRepo $mogouChapterRepo,
        protected readonly SubMogouActionRepo $subMogouActionRepo
    )
    {

    }

    public function index(Request $request)
    {
        $subMogouQuery = $this->subMogouActionRepo->getChaptersQuery($request->mogou_id);

        $mogouChapters = $subMogouQuery->paginate(10);

        return response()->json([
            'mogou_chapters' => $mogouChapters
        ]);
    }
}
