<?php

// config for HydraStorage/HydraStorage
return [

    'provider' => env('FILESYSTEM_DISK', 'local'),

    'compressed_quality' => env('COMPRESSED_QUALITY', 60),
];
