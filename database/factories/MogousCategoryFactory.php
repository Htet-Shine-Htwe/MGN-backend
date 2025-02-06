<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MogousCategory>
 */
class MogousCategoryFactory extends Factory
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
            'category_id' => rand(1, config('control.test.categories_count')),
        ];
    }
}
