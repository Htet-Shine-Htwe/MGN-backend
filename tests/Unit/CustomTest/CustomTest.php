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
            $mediaOption = MediaOption::create()
            // ->setWaterMark($watermark, 'bottom-right',90)
            ->get();

            // $file = new UploadedFile(Storage::disk('local')->path('bannerSlot.gif'), 'bannerSlot.gif', null, null, true);
            $file = new UploadedFile(Storage::disk('local')->path('lg.png'), 'lg.png', null, null, true);

            $this->storeMedia($file,'solo_leveling_chapter_1',true, $mediaOption);
        }
    })();

})->skip();

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
