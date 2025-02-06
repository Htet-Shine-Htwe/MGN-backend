<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialChannel>
 */
class SocialChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'token_key' => $this->faker->uuid,
            'type' => $this->faker->randomElement([1, 2]),
            'is_active' => $this->faker->boolean,

        ];
    }
}
