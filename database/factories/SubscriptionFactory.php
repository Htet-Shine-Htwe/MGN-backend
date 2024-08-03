<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title' => $this->faker->randomElement(['Free', 'Basic', 'Premium']),
            'max' => $this->faker->numberBetween(50, 100),
            'price' => $this->faker->numberBetween(100, 10000) . 00,
            'duration' => $this->faker->randomElement([30, 60, 90, 120, 180, 365])
        ];
    }
}
