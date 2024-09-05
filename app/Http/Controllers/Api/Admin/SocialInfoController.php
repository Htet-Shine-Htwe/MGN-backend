<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialInfoRequest;
use App\Repo\Admin\SocialInfo\SocialInfoRepo;
use Illuminate\Http\Request;

class SocialInfoController extends Controller
{
    public function __construct(protected SocialInfoRepo $socialInfoRepo)
    {

    }

    public function index()
    {
        return $this->socialInfoRepo->all();
    }

    public function store(SocialInfoRequest $request)
    {
        $socialInfo = $this->socialInfoRepo->create($request->all());
        return response()->json([
            'success' => true,
            'social_info' => $socialInfo
        ], 201);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
        ]);


        $socialInfo = $this->socialInfoRepo->update($id, $request->all());
        return response()->json([
            'success' => true,
            'social_info' => $socialInfo
        ], 200);
    }

    public function delete(Request $request,$id)
    {
         $this->socialInfoRepo->delete($id);

        return response()->json([
            'success' => true,
        ], 200);
    }


    public function banners(Request $request)
    {
        $banners = $this->socialInfoRepo->getBanners();
        return response()->json([
            'success' => true,
            'social_info' => $banners
        ], 200);
    }
}
