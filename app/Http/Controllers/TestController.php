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

        $media = $this->storeMedia($request->file('file'), 'new', true, $option);

        return response()->json($media);

    }

}
