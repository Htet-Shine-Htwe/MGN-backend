<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BotSocialChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            BotPublisherSeeder::class,
            SocialChannelSeeder::class,
        ]);

        $botPublishers = \App\Models\BotPublisher::all();
        $socialChannels = \App\Models\SocialChannel::all();

        // minor test ,decided to  not implement insert approach
        foreach ($botPublishers as $botPublisher) {
                \App\Models\BotSocialChannel::factory()->create([
                    'bot_publisher_id' => $botPublisher->id,
                    'social_channel_id' => $socialChannels->random()->id,
                ]);
        }
    }
}
