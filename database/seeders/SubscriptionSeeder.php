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
            "Free",
            "Basic",
            "Premium",
            "Thrive Club",
            "Level Up",
            "The Inner Circle",
            "Mastery Mode",
            "Curated Collection",
            "The Monthly Muse",
            "Fuel Your Focus",
            "Self-Care Sanctuary",
            "Adventure Awaits",
            "The VIP Vault",
            "Endless Escape",
            "The Knowledge Box",
            "Maker's Playground",
            "The Sustainable Switch",
            "The Early Bird Club"
        ];

        foreach ($subscription_collection as $subscription) {
            \App\Models\Subscription::factory()->create([
                'title' => $subscription,
            ]);
        }
    }
}
