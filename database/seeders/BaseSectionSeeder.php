<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BaseSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $base_sections = ["Hero Highlight Slider"];

        foreach ($base_sections as $section) {
            \App\Models\BaseSection::create([
                "section_name" => $section,
                "section_description" => "This is a description for the $section",
                "component_limit" => 10,
            ]);
        }

    }
}
