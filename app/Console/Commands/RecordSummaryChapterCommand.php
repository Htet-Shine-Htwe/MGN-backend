<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class RecordSummaryChapterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:chapter {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $date = $this->argument('date');

        $now = Carbon::parse($date)->startOfMonth();

        if ($now->isToday()) {
            $startDate = $now->copy()->subMonths(2)->startOfMonth();
            $endDate = $now->copy()->subMonths(2)->endOfMonth();
            \Log::channel("chapter_summary")->info('Recording summary chapter analysis', ['start_date' => $startDate, 'end_date' => $endDate]);
            $this->info('Recording summary chapter analysis');
            dispatch(new \App\Jobs\RecordSummaryChapterAnalysis($startDate, $endDate))->onQueue('summary');
            $this->info('Summary chapter analysis recorded successfully');
        }else{
            $this->info('Please provide the correct date (start of the month) to record the summary chapter analysis');
        }
    }
}
