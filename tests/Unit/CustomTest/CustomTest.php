<?php

use Symfony\Component\HttpFoundation\File\UploadedFile;
use HydraStorage\HydraStorage\Service\Option\MediaOption;
use HydraStorage\HydraStorage\Service\Snap\ImageSnap;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Support\Facades\Storage;

uses()->group('thiri');

test('test', function() {

    config(['hydrastorage.provider' => 'local']);

    $instance = (new class {
        use HydraMedia;

        public function __invoke()
        {
            $watermark = Storage::disk('local')->path('watermark.jpg');
            $mediaOption = MediaOption::create()
            ->setPrefixFileName('solo_leveling_chapter_1')
            ->setWaterMark($watermark, 'bottom-right',90)
            ->setQuality(10)
            ->get();

            $file = new UploadedFile(Storage::disk('local')->path('bg_03.jpg'), 'bg_03.jpg', 'image/jpeg', null, true);

            $this->storeMedia($file,'solo_leveling_chapter_1',true, $mediaOption);
        }
    })();

});

// test('test-watermark', function() {

//     config(['hydrastorage.provider' => 'local']);

//     $instance = (new class {
//         use HydraMedia;

//         public function __invoke()
//         {
//             $mediaOption = MediaOption::create()
//             ->resize('small')
//             ->setQuality(80)
//             ->get();

//             $file = new UploadedFile(Storage::disk('local')->path('logo.png'), 'logo.png', 'image/png', null, true);

//             $this->storeMedia($file,'watermark',true, $mediaOption);
//         }
//     })();

// });
