<?php

namespace Database\Seeders;

use App\Models\ApplicationConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApplicationConfig::create([
            'title' => 'Mogou',
            'logo' => 'https://mogou.com/logo.png',
        ]);
    }
}
