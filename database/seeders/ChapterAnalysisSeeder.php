<?php

namespace Database\Seeders;

use App\Models\ChapterAnalysis;
use App\Models\Mogou;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChapterAnalysisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('database.default') == 'sqlite') {
           DB::statement('PRAGMA foreign_keys=OFF;');
        } 
        for ($k = 0; $k < 4; $k++) {
            $insert = [];
            for ($i = 1; $i <= config('control.test.chapter_analysis_count'); $i++) {
                $should_user_id = fake()->boolean(50);
                $mogou = Mogou::inRandomOrder()->first();
                $mogou_id = $mogou->id;
                $sub_mogou_id = $mogou->subMogous($mogou->rotation_key)->inRandomOrder()->first()?->id ?? 1;
                $insert[] = [
                    'mogou_id' => $mogou_id,
                    'sub_mogou_id' => $sub_mogou_id,
                    'ip' =>  fake()->ipv4,
                    'date' => fake()->dateTimeBetween('2025-01-01', 'now')->format('Y-m-d H:i:s'),
                    'user_id' => $should_user_id ? config("control.test.users_count") : null,
                ];
            }
            $chunks = array_chunk($insert, 150);
            foreach ($chunks as $chunk) {
                ChapterAnalysis::insert($chunk);
            }
        }


    }
}
