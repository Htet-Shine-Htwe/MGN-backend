<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BotSocialChannel>
 */
class BotSocialChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bot_publisher_id' => rand(1, 5),
            'social_channel_id' => rand(1, 5),
        ];
    }
}
