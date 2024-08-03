<?php

namespace Database\Seeders;

use App\Enum\MogouFinishStatus;
use App\Enum\MogousStatus;
use App\Enum\MogouTypeEnum;
use App\Models\Mogou;
use App\Services\Api\DataClient;
use App\Services\Partition\TablePartition;
use Illuminate\Database\Seeder;
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

            $outsource_data = DataClient::getMangaData();

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
                    'rating' => fake()->randomFloat(1, 0, 5),
                    'released_year' => fake()->year,
                    'released_at' => fake()->dateTimeThisYear,
                ];
            }

            Mogou::insert($data);
        }

    }
}

