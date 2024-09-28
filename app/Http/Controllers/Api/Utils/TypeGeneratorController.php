<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TypeGeneratorController extends Controller
{

    public function generateName(Request $request)
    {
        $random_user_name = $this->generateRandomString();

        if(User::where('name', $random_user_name)->exists() ) { return $this->generate();
        }

        return response()->json(
            [
            'random_user_name' => $random_user_name
            ]
        );
    }

    private function generateRandomString()
    {
        return \Faker\Factory::create()->unique()->name .rand(100, 999);
    }

}
