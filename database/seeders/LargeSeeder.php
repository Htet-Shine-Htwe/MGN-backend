<?php

namespace Database\Seeders;

use App\Enum\MogousStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '2048M');

        $count= 5000;
        $large_seed = [];


        // larget seed should be mutliply of count

        for ($i = 0; $i < 10; $i++) {
            $large_seed[] = $i * $count;
        }

        foreach ($large_seed as $offset) {
            $this->largeSeeding($offset);
            $this->command->info("Seeded {$offset} rows.");
        }

    }

    protected function largeSeeding($offset = 0)
    {
        $mogous = [];
        $subMogous = [];
        $mogous_category = [];
        $total_mogou = 5000;
        $rand = rand(0,7);
        for ($i = $offset; $i < $offset + $total_mogou; $i++) {
            $title = fake()->sentence($rand) . ' ' . $i . $i . fake()->sentence(4);
            $mogous[] = [
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => fake()->paragraph(4),
                'author' => fake()->name,
                'cover' => fake()->imageUrl(),
                'status' => MogousStatus::getRandomStatus(),
                'release_year' => fake()->year,
                'released_at' => fake()->dateTimeThisYear,
            ];

            for ($j = 0; $j < 2; $j++) {
                $mogous_category[] = [
                    'mogou_id' => $i + 1,
                    'category_id' => fake()->numberBetween(1, 10),
                ];
            }

            for ($j = 0; $j < 5; $j++) {
                $sub_title = $title . " chapter $j";
                $subMogous[] = [
                    'title' => $sub_title,
                    'slug' => Str::slug($sub_title),
                    'description' => fake()->paragraph(4),
                    'cover' => fake()->imageUrl(),
                    'status' => MogousStatus::getRandomStatus(),
                    'chapter_number' => fake()->numberBetween(1, 100),
                    'views' => fake()->numberBetween(1, 1000),
                    'mogou_id' => $i + 1,
                ];
            }
        }

        $this->chunkAndInsert($mogous, 'mogous');
        $this->chunkAndInsert($subMogous, 'sub_mogous');
        $this->chunkAndInsert($mogous_category, 'mogous_categories');
    }

    protected function chunkAndInsert(array $data, string $table, int $chunkSize = 1000): void
    {
        $chunks = array_chunk($data, $chunkSize);
        foreach ($chunks as $chunk) {
            DB::table($table)->insert($chunk);
        }

    }
}
