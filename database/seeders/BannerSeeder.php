<?php

namespace Database\Seeders;

use App\Enum\SocialInfoType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // truncate the table
        \App\Models\SocialInfo::truncate();
        $data = [
            [
                'name' => 'Home Page Top Banner',
                'cover_photo' => 'banner-1.gif',
                'type' => SocialInfoType::Banner->value,
                'redirect_url' => 'https://google.com',
            ],
            [
                'name' => "Home Page Bottom Banner",
                'cover_photo' => 'banner-2.gif',
                'type' => SocialInfoType::Banner->value,
                'redirect_url' => 'https://google.com'

            ],
            [
                'name' => "Home Page Middle Banner",
                'cover_photo' => 'banner-3.gif',
                'type' => SocialInfoType::Banner->value,
                'redirect_url' => 'https://google.com'
            ],
        ];

        foreach ($data as $datum) {
            \App\Models\SocialInfo::create($datum);
        }
    }
}
