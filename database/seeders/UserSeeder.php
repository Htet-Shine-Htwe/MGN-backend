<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_dump =[];

        $total = config('control.test.users_count');

        for ($i = 0; $i <= $total; $i++) {
            $user_dump[] = [
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' =>  bcrypt('password'),
                'current_subscription_id' => rand(1, 4),
                'user_code' => 'user' . $i,
                'subscription_end_date' => now()->subDays(rand(1, 30)),
                "last_login_at" => now()->subDays(rand(1, 30)),
                'avatar_id' => rand(1, 5),
                'background_color' => '#' . substr(md5(rand()), 0, 6),
            ];
        }

        $array_chunk = array_chunk($user_dump, 1000);

        foreach ($array_chunk as $chunk) {
            \App\Models\User::insert($chunk);
        }

    }
}
