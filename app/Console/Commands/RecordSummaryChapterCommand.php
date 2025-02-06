<?php

namespace App\Console\Commands;

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

        // get the start of the week for the given date
        $startOfWeek = \Carbon\Carbon::parse($date)->startOfWeek();
        $endOfWeek = \Carbon\Carbon::parse($date)->endOfWeek();

        $this->info('Recording summary chapter analysis');
        dispatch(new \App\Jobs\RecordSummaryChapterAnalysis($startOfWeek,$endOfWeek))->onQueue('summary');
        $this->info('Summary chapter analysis recorded successfully');
    }
}
