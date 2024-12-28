<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Mogou;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MogousCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(config('database.default') == 'sqlite') {
            $one = $this->loopOver(config('control.test.mogous_count'), config('control.test.categories_count'));
            $two = $this->loopOver(config('control.test.mogous_count'), config('control.test.categories_count'));
            $mogousCategories = array_merge($one, $two);

        } else {
            $mogousCategories = array_merge($this->loopOver(Mogou::count(), Category::count()), $this->loopOver(Mogou::count(), Category::count()),$this->loopOver(Mogou::count(), Category::count()),$this->loopOver(Mogou::count(), Category::count()),$this->loopOver(Mogou::count(), Category::count()),$this->loopOver(Mogou::count(), Category::count()),$this->loopOver(Mogou::count(), Category::count()));
        }

        DB::table('mogous_categories')->insert($mogousCategories);
    }

    protected function loopOver($mogousCount, $categoriesCount) : array
    {
        $mogousCategories = [];

        for ($i = 1; $i <= $mogousCount; $i++) {
            $mogousCategories[] = [
                'mogou_id' => $i,
                'category_id' => rand(1, $categoriesCount),
            ];
        }

        return $mogousCategories;
    }
}
