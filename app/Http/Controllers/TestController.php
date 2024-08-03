<?php

namespace App\Http\Controllers;

use HydraStorage\HydraStorage\Service\Option\MediaOption;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use HydraMedia;

    public function test(Request $request)
    {
        $option = (new MediaOption('import',20,400,400,'png'));


        $media = $this->storeMedia($request->file('file'), 'new',true,$option);

        return response()->json($media);

    }

    public function testRequest(Request $request)
    {
        return response()->json([
            'message' => "request was successful"
        ]);
    }
}
