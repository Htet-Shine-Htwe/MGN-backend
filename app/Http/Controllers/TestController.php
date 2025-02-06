<?php

namespace App\Http\Controllers;

use HydraStorage\HydraStorage\Service\Option\MediaOption;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use HydraMedia;

    public function test(Request $request): JsonResponse
    {
        $option = MediaOption::create();

        $media = $this->removeMedia("public/user_avatars/user_sample_5.png");

        return response()->json($media);

    }

}
