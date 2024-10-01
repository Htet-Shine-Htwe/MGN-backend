<?php

namespace Database\Seeders;

use App\Models\Mogou;
use App\Models\SubMogou;
use App\Models\SubMogouImage;
use App\Services\Partition\PartitionFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubMogouSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PartitionFactory::createInstancePartition(SubMogou::class, 2);


        if(config('database.default') == 'sqlite') {
            for($i = 1; $i <= config('control.test.mogous_count'); $i++) {
                $total_chapter = 5;
                for($j = 1; $j <= $total_chapter; $j++) {
                    SubMogou::factory()->create([
                        'mogou_id' => $i,
                        'chapter_number' => $j,
                    ]);
                }
            }
        } else {

            $alpha_mogou = Mogou::where('rotation_key', 'alpha')->pluck('id')->toArray();
            $beta_mogou = Mogou::where('rotation_key', 'beta')->pluck('id')->toArray();

            $wp =[
                "alpha" => $alpha_mogou,
                "beta" => $beta_mogou,
            ];

            // map the wp

            foreach($wp as $key => $mogou) {
                $sub_mogou_insert = [];
                $sub_mogou_images_insert = [];
                for($i = 0; $i < count($mogou); $i++) {
                    $total_chapter = rand(1, 20);
                    for($j = 1; $j <= $total_chapter; $j++) {
                        $sub_mogou_insert[] = [
                            'title' => 'Chapter ' . $j . ' of Mogou ' . $mogou[$i],
                            "slug" => "chapter-" . $j . "-of-mogou-" . $mogou[$i],
                            'cover' => 'cover.jpg',
                            'mogou_id' => $mogou[$i],
                            'chapter_number' => $j,
                        ];

                        $total_images = rand(20, 40);
                        for($k = 1; $k <= $total_images; $k++) {
                            $sub_mogou_images_insert[] = [
                                'sub_mogou_id' => $j,
                                'path' => 'image.jpg',
                                'page_number' => $k,
                            ];
                        }

                    }

                }
                SubMogou::insert($sub_mogou_insert);
                SubMogouImage::insert($sub_mogou_images_insert);
                DB::statement('insert into '.$key.'_sub_mogous select * from sub_mogous');
                DB::statement('insert into '.$key.'_sub_mogou_images select * from sub_mogou_images');

                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                SubMogouImage::truncate();
                SubMogou::truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

        }
    }
}
