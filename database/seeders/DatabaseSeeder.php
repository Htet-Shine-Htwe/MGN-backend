<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            ApplicationConfigSeeder::class,
            AdminSeeder::class,
            PermissionSeeder::class,
            AdminPermissionSeeder::class,
            SubscriptionSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            MogouSeeder::class,
            MogousCategorySeeder::class,
            SubMogouSeeder::class,
            BannerSeeder::class,
            BaseSectionSeeder::class,
        ]);
    }
}
