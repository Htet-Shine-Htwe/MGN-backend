<?php

namespace Database\Seeders;

use App\Models\UserFavorite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserFavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $total_mogou = config("control.test.mogous_count");
        $total_user = config("control.test.users_count");

        $data = [];

        for ($i = 1; $i <= $total_user; $i++) {
            for ($j = 1; $j <= 10; $j++) {
                // created_at and updated_at are randomly between this year
                $day =now()->subDays(rand(1, 365));
                $data[] = [
                    'user_id' => $i,
                    'mogou_id' => rand(1, $total_mogou),
                    'created_at' => $day,
                    'updated_at' => $day,
                ];
            }
        }

        $data = array_map("unserialize", array_unique(array_map("serialize", $data)));

        UserFavorite::insert($data);
    }
}
