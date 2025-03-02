<?php

return [
    'test' => [
        'users_count' => 180,
        'mogous_count' => 80,
        'categories_count' => 22,
    ],
    'mongou_storage' => "local",

    "cacheMode" => false,

    'mogou' => [
        'cover' => [
            'path' => 'mogou/cover',
            'disk' => 'public',
            'is_public' => true,
        ],
        'chapter' => [
            'path' => 'mogou/chapter',
            'disk' => 'public',
            'is_public' => true,
        ],
        'sub_mogou' => [
            'cover' => [
                'path' => 'mogou/sub_mogou/cover',
                'disk' => 'public',
                'is_public' => true,
            ],
            'chapter' => [
                'path' => 'mogou/sub_mogou/chapter',
                'disk' => 'public',
                'is_public' => true,
            ],
        ],
    ],

    'cache_key' => [
        'homepage' => [
            'tags' => "homepage",
            'most_viewed' => 'homepage_most_viewed',
            'last_uploaded' => 'homepage_last_uploaded',
            'carousel' => 'hero_highlight_slider',
            'recommend' => 'main_page_recommended',
        ]
    ]
];
