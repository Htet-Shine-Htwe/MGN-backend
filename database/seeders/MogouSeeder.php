<?php

namespace Database\Seeders;

use App\Enum\MogouFinishStatus;
use App\Enum\MogousStatus;
use App\Enum\MogouTypeEnum;
use App\Models\Mogou;
use App\Services\Api\DataClient;
use App\Services\Partition\TablePartition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MogouSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(config('database.default') == 'sqlite') {
            Mogou::factory()->count(config('control.test.mogous_count'))->create();
        } else {
            $data = [];

            $outsource_folder = storage_path('app/public/outsource');
            $file_name = 'manga.json';

            if (!file_exists($outsource_folder)) {
                mkdir($outsource_folder, 0777, true);
            }

            if (!file_exists($outsource_folder . '/' . $file_name)) {
                $outsource_data = DataClient::getMangaData();
                file_put_contents($outsource_folder . '/' . $file_name, json_encode($outsource_data));
                Log::info('Manga data fetched from API and saved to local storage');
            }else{
                $outsource_data = json_decode(file_get_contents($outsource_folder . '/' . $file_name), true);
                Log::info('Manga data fetched from local storage');
            }

            \Log::info($outsource_data);

            foreach ($outsource_data as $manga) {
                $title  = str_replace('"', '', $manga['title']);
                $data[] = [
                    'rotation_key' => TablePartition::getRandomRotationKey(),
                    'title' =>  $title,
                    'slug' => Str::slug($title) . '-' . Str::random(5),
                    'description' =>fake()->paragraph(4),
                    'author' => fake()->name,
                    'cover' => $manga['picture_url'],
                    'status' => MogousStatus::getRandomStatus(),
                    'finish_status' =>  MogouFinishStatus::getRandomStatus(),
                    'mogou_type' => MogouTypeEnum::getRandomMogouType(),
                    'legal_age' => fake()->boolean,
                    'rating' => fake()->randomFloat(0, 0, 5),
                    'released_year' => fake()->year,
                    'released_at' => fake()->dateTimeThisYear,
                    'created_at' => fake()->dateTimeThisYear,
                ];
            }

            Mogou::insert($data);
        }

    }
}

