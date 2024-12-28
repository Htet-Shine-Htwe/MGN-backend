<?php

namespace App\Console\Commands;

use App\Models\Mogou;
use App\Models\SubMogou;
use Illuminate\Console\Command;

class CalculateMogouChapters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:mogou-chapters';

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
        $this->info('Calculating Mogou chapters...');

        $mogous = Mogou::all();

        // log the total mogous

        $chunked = $mogous->chunk(10);

        foreach ($chunked as $chunk) {
            foreach ($chunk as $mogou) {

                $rotation_key = $mogou->rotation_key;

                $sub_mogou = new SubMogou();
                $table = $sub_mogou->getPartition($rotation_key);

                $sub_mogou->setTable($table);


                // log subb mogou table
                $this->info('Sub mogou table: ' . $table);

                $total_sub_mogou_chapters = $sub_mogou->where('mogou_id', $mogou->id)->count();

                $this->info('Sub mogou count: ' . $total_sub_mogou_chapters);


                $mogou->update(['total_chapters' => $total_sub_mogou_chapters]);
            }
        }

        $this->info('Mogou chapters calculated successfully.');
    }
}
