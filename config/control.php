<?php

return [
    'test' => [
        'users_count' => 80,
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
];
