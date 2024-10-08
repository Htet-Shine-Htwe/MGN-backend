<?php

namespace Database\Seeders;

use App\Models\ChildSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BaseSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $base_sections = ["hero_highlight_slider"];

        foreach ($base_sections as $section) {
            \App\Models\BaseSection::create([
                "section_name" => $section,
                "section_description" => "This is a description for the $section",
                "component_limit" => 10,
            ]);
        }

        ChildSection::factory( 8)->create([
            "base_section_id" => 1,
        ]);

    }
}
