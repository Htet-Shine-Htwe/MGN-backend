<?php

namespace Database\Seeders;

use App\Models\Mogou;
use App\Models\SubMogou;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubMogouSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dbPartition = new class extends Model {

            use \App\Traits\DbPartition;

            protected string $baseTable = 'sub_mogous';

            protected string $partition_prefix = 'sub_mogous';

        };

        $dbPartition->createPartition(); // creating alpha partition table
        $dbPartition->createPartition();  // creating beta partition table

        if(config('database.default') == 'sqlite') {
            for($i = 1; $i <= config('control.test.mogous_count'); $i++) {
                $total_chapter = 5;
                for($j = 1; $j <= $total_chapter; $j++) {
                    SubMogou::factory()->create([
                        'mogou_id' => $i,
                        'chapter_number' => $j,
                    ]);
                }
            }
        } else {


            for($i = 1; $i <= Mogou::count(); $i++) {
                // random number of chapters
                $total_chapter = rand(20, 200);
                for($j = 1; $j <= $total_chapter; $j++) {
                    SubMogou::factory()->create([
                        'title' => 'Chapter ' . $j . ' of Mogou ' . $i,
                        'mogou_id' => $i,
                        'chapter_number' => $j,
                    ]);
                }
            }

        }

        // insert into alpha_sub_mogous select * from sub_mogous;

        DB::statement('insert into alpha_sub_mogous select * from sub_mogous');
        DB::statement('insert into beta_sub_mogous select * from sub_mogous');
    }
}
