<?php

namespace App\Jobs;

use App\Models\ChapterAnalysis;
use App\Models\ChapterAnalysisSummary;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecordSummaryChapterAnalysis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct( protected DateTime $start_time, protected DateTime $end_time)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $groupedChapters = $this->getGroupedChapters();

        $groupedChapters->each(function ($chapter) {
            $this->processChapter($chapter);
        });

        $this->logSummaryRecorded();
    }

    /**
     * Get grouped chapters by mogou_id and sub_mogou_id.
     *
     * @return \Illuminate\Support\Collection<int, ChapterAnalysis>
     */
    protected function getGroupedChapters() : \Illuminate\Support\Collection
    {
        return ChapterAnalysis::query()
            ->select('mogou_id', 'sub_mogou_id', DB::raw('count(*) as total_views'))
            ->whereBetween('date', [$this->start_time, $this->end_time])
            ->groupBy('mogou_id', 'sub_mogou_id')
            ->get();
    }


    protected function processChapter(ChapterAnalysis $chapter) : void
    {
        DB::transaction(function() use ($chapter) {
            $this->updateOrCreateSummary($chapter);
            $this->deleteChapterAnalysis($chapter);
            $this->logChapterSummary($chapter);
        }, 2);
    }

    /**
     * Update or create chapter analysis summary.
     *
     * @param $chapter
     */
    protected function updateOrCreateSummary(ChapterAnalysis $chapter) : void
    {
        ChapterAnalysisSummary::updateOrCreate(
            [
                'mogou_id' => $chapter->mogou_id,
                'sub_mogou_id' => $chapter->sub_mogou_id,
            ],
            [
                'total_views' => $chapter->total_views ?? 0,
                'start_date' => now()->startOfWeek(),
                'end_date' => now()->endOfWeek(),
            ]
        );
    }

    /**
     * Delete chapter analysis records.
     *
     * @param $chapter
     */
    protected function deleteChapterAnalysis(ChapterAnalysis $chapter) : void
    {
        ChapterAnalysis::where('mogou_id', $chapter->mogou_id)
            ->where('sub_mogou_id', $chapter->sub_mogou_id)
            ->whereBetween('date', [$this->start_time, $this->end_time])
            ->delete();
    }


    protected function logChapterSummary(ChapterAnalysis $chapter) : void
    {
        $total_views = $chapter->total_views ?? 0;
        Log::channel("chapter_summary")
            ->info("Chapter Summary for Mogou ID: {$chapter->mogou_id} and Sub Mogou ID: {$chapter->sub_mogou_id} with count: {$total_views } has been updated.");
    }


    protected function logSummaryRecorded() : void
    {
        Log::channel("chapter_summary")
            ->info("Chapter Summary has been successfully recorded for week " . now()->weekOfYear);
    }
}
