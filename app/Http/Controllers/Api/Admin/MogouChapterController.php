<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mogou;
use App\Models\SubMogou;
use App\Repo\Admin\MogouChapter\MogouChapterRepo;
use App\Repo\Admin\SubMogouRepo\SubMogouActionRepo;
use App\Services\Report\ChapterReport;
use Illuminate\Http\Request;

class MogouChapterController extends Controller
{
    public function __construct(
        protected readonly MogouChapterRepo $mogouChapterRepo,
        protected readonly SubMogouActionRepo $subMogouActionRepo
    ) {

    }

    public function index(Request $request)
    {
        $subMogouQuery = $this->subMogouActionRepo->getChaptersQuery($request->mogou);

        $mogouChapters = $subMogouQuery->paginate(10);

        return response()->json(
            [
            'mogou_chapters' => $mogouChapters
            ]
        );
    }

    public function chapterAnalysis(Request $request)
    {
        $mogou = Mogou::where('slug', $request->mogou)->first();


        $chapterReport =  (new ChapterReport($mogou));

        $total_views = $chapterReport->getTotalViews();
        $total_chapters = $chapterReport->getTotalChapters();

        $res = [
            [
                'label' => 'Total Views',
                'value' => $total_views
            ],
            [
                'label' => 'Total Chapters',
                'value' => $total_chapters
            ]
        ];

        return response()->json(
            [
            'chapter_analysis' => $res
            ]
        );

    }
}
