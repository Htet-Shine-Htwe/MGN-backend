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
    public function handle() :void
    {

        $date = $this->argument('date');

        $endDate = Carbon::parse($date)->subDays(15);
        $startDate = $endDate->copy()->subDays(15);


        $this->info('Recording summary chapter analysis');
        dispatch(new \App\Jobs\RecordSummaryChapterAnalysis($startDate,$endDate))->onQueue('summary');
        $this->info('Summary chapter analysis recorded successfully');
    }
}
