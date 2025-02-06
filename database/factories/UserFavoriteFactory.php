<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserFavorite>
 */
class UserFavoriteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_count = config('control.test.users_count');
        $mogou_count = config('control.test.mogous_count');

        return [
            'user_id' => fake()->numberBetween(1, $user_count),
            'mogou_id' => fake()->numberBetween(1, $mogou_count),
        ];
    }
}
