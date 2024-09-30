<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscription_collection = [
            [
                "title" => "Free",
                "duration" => 30,
            ],
            [
                "title" => "Basic",
                "duration" => 60,
            ],
            [
                "title" => "Premium",
                "duration" => 90,
            ],
            [
                "title" => "Promotion",
                "duration" => 10,
            ],
            [
                "title" => "Special",
                "duration" => 180,
            ]
        ];

        foreach ($subscription_collection as $subscription) {
            \App\Models\Subscription::factory()->create([
                'title' => $subscription['title'],
                'duration' => $subscription['duration'],
            ]);
        }
    }
}
