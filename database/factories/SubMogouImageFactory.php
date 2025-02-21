<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubMogouImage>
 */
class SubMogouImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sub_mogou_id' => rand(),
            'path' => 'image.jpg',
            'position' => chr(rand(97, 122)),
        ];
    }
}
