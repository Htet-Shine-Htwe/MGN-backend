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
                "price" => 0,
            ],
            [
                "title" => "Basic",
                "duration" => 60,
                "price" => 2500,
            ],
            [
                "title" => "Premium",
                "duration" => 90,
                "price" => 5000,
            ],
            [
                "title" => "Promotion",
                "duration" => 10,
                "price" => 1000,
            ],
            [
                "title" => "Special",
                "duration" => 180,
                'price' => 10000,
            ],
            [
                "title"  => 'Life-Time',
                "duration" => 0,
                'price' => 100000,
            ]
        ];

        foreach ($subscription_collection as $subscription) {
            \App\Models\Subscription::factory()->create([
                'title' => $subscription['title'],
                'duration' => $subscription['duration'],
                'price' => $subscription['price'],
            ]);
        }
    }
}
