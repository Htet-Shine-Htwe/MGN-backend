<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class CategorySeeder extends Seeder {
    /**
    * Run the database seeds.
    */

    public function run(): void {
        $genres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
            'Magic',
            'Mystery',
            'Psychological',
            'Romance',
            'Sci-Fi',
            'Slice of Life',
            'Sports',
            'Supernatural',
            'Thriller',
            'Martial Arts',
            'Historical',
            'Mecha',
            'Military',
            'Music',
            'Parody',
            'Shoujo',
            'Shoujo Ai',
            'Shounen',
            'Shounen Ai',
            'Seinen',
            'Josei',
            'School',
            'Ecchi',
            'Harem',
            'Yaoi',
            'Yuri',
            'Isekai',
            'Post-Apocalyptic',
            'Tragedy',
            'Vampire',
            'Demons',
            'Police',
            'Samurai',
            'Game',
            'Space',
            'Kids',
            'Super Power',
            'Cars',
            'Dementia',
            'Doujinshi'
        ];

        $data = [];

        foreach ( $genres as $genre ) {
            $data[] = [
                'title' => $genre,
                'slug' => Str::slug( $genre ),
            ];
        }

        Category::insert( $data );

        // Category::factory()->count( config( 'control.test.categories_count' ) )->create();
    }
}
