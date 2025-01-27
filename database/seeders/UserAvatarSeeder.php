<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\UserAvatar::factory()->create([
                'avatar_name' => "avatar-$i",
                'avatar_path' => "user_sample_$i.png"
            ]);
        }
    }
}
