<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Mogou;
use App\Models\SubMogou;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ViewCountController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try{
            $rk = $request->mogou_key;
            $instance = new SubMogou();
            $instance->setTable($rk."_sub_mogous");

            $subMogou = $instance->where('slug', $request->sub_mogou_slug)->firstOrFail();

            $subMogou->increment('views');

            return response()->json(['message' => 'View count incremented']);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'Cant process'], 500);
        }
    }
}
