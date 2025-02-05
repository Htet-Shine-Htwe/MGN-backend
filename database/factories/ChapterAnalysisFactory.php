<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChapterAnalysis>
 */
class ChapterAnalysisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mogou_id' => rand(1, config('control.test.mogous_count')),
            'sub_mogou_id' => rand(1,100),
            'ip' => $this->faker->ipv4,
            'date' => $this->faker->dateTimeThisYear,
        ];
    }
}
