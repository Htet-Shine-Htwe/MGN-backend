<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_count = config('control.test.users_count');

        $user_subscription_dump = [];
        for ($i = 1; $i <= $user_count; $i++) {

            for ($j = 0; $j <= 10; $j++) {
                $user_subscription_dump[] = [
                    'user_id' => $i,
                    'subscription_id' => rand(1, 6),
                    'created_at' => now()->subDays(rand(1, 90)),
                ];
            }
        }

        $array_chunk = array_chunk($user_subscription_dump, 1000);

        foreach ($array_chunk as $chunk) {
            \App\Models\UserSubscription::insert($chunk);
        }
    }
}
