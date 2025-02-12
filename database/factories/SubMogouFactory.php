<?php

namespace Database\Factories;

use App\Enum\MogousStatus;
use App\Models\SubMogou;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubMogou>
 */
class SubMogouFactory extends Factory
{

    protected $model = SubMogou::class;


    public function definition(): array
    {
        $title =  $this->faker->sentence(4) . ' ' . $this->faker->sentence(2);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(4),
            'cover' => $this->faker->imageUrl(),
            'status' => MogousStatus::getRandomStatus(),
            'chapter_number' => $this->faker->numberBetween(1, 100),
            'views' => $this->faker->numberBetween(1, 1000),
            'subscription_only' => $this->faker->numberBetween(0, 1),
            'subscription_collection' => json_encode($this->faker->numberBetween(1, 5)),
            'mogou_id' => rand(1, config('control.test.mogous_count')),
            'creator_id' => rand(1,3),
            'creator_type' => 'App\Models\Admin',
        ];
    }
}
