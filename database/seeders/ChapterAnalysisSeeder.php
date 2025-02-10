<?php

namespace Database\Seeders;

use App\Models\ChapterAnalysis;
use App\Models\Mogou;

use Illuminate\Database\Seeder;

class ChapterAnalysisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mogouCount = Mogou::count();
        for ($k = 0; $k < 4; $k++) {
            $insert = [];
            for ($i = 1; $i <= 20000; $i++) {
                $should_user_id = fake()->boolean(50);
                $insert[] = [
                    'mogou_id' => rand(1,$mogouCount),
                    'sub_mogou_id' => rand(1, 10),
                    'ip' =>  fake()->ipv4,
                    'date' => fake()->dateTimeBetween('2024-01-01', 'now')->format('Y-m-d H:i:s'),
                    'user_id' => $should_user_id ? config("control.test.users_count") : null,
                ];
            }
            $chunks = array_chunk($insert, 2000);
            foreach ($chunks as $chunk) {
                ChapterAnalysis::insert($chunk);
            }
        }


    }
}
