<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ];
        $uploader = [
            [
                'name' => 'Uploader One',
                'email' => 'uploaderOne@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Uploader Two',
                'email' => 'uploaderTwo@gmail.com',
                'password' => bcrypt('password'),
            ]
        ];
        Admin::insert(array_merge([$admin], $uploader));
    }
}
