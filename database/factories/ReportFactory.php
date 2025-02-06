<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => $this->faker->sentence,
            "description" => $this->faker->paragraph,
            "current_url" => $this->faker->url,
            "status" => $this->faker->randomElement([0,1,2]),
            "image" => $this->faker->imageUrl(),
            'created_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
