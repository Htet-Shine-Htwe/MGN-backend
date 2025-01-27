<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BaseSection>
 */
class BaseSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "section_name" => $this->faker->word,
            "section_description" => $this->faker->sentence,
            "component_limit" => $this->faker->numberBetween(1, 100),
        ];
    }
}
