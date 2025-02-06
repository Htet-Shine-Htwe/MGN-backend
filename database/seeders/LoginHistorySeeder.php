<?php

namespace Database\Seeders;

use App\Models\LoginHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoginHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_count = config("control.test.users_count");

        $data = [];

        for ($i = 1; $i <= $user_count; $i++) {

            $loop = rand(10, 40);
            for ($j = 1; $j <= $loop; $j++) {
                $data[] = [
                    'user_id' => $i,
                    'location' => fake()->city,
                    'country' => fake()->country,
                    'device' => 'device'.$j,
                    'login_at' => now()->subDays($j),
                ];
            }
        }

        $chunk = array_chunk($data, 1000);

        foreach ($chunk as $data) {
            LoginHistory::insert($data);
        }
    }
}
