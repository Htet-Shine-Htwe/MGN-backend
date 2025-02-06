<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialInfoRequest;
use App\Models\SocialInfo;
use App\Repo\Admin\SocialInfo\SocialInfoRepo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialInfoController extends Controller
{
    public function __construct(protected SocialInfoRepo $socialInfoRepo)
    {

    }

    /**
     * index
     *
     * @return Collection<int, SocialInfo>
     */
    public function index(): Collection
    {
        return $this->socialInfoRepo->all();
    }

    public function store(SocialInfoRequest $request): JsonResponse
    {
        $socialInfo = $this->socialInfoRepo->create($request->all());
        return response()->json(
            [
            'success' => true,
            'social_info' => $socialInfo
            ], 201
        );
    }

    public function update(Request $request,string $id): JsonResponse
    {

        $request->validate(
            [
            ]
        );


        $socialInfo = $this->socialInfoRepo->update($id, $request->all());
        return response()->json(
            [
            'success' => true,
            'social_info' => $socialInfo
            ], 200
        );
    }

    public function delete(Request $request,string $id): JsonResponse
    {
         $this->socialInfoRepo->delete($id);

        return response()->json(
            [
            'success' => true,
            ], 200
        );
    }


    public function banners(Request $request): JsonResponse
    {
        $banners = $this->socialInfoRepo->getBanners();
        return response()->json(
            [
            'success' => true,
            'social_info' => $banners
            ], 200
        );
    }
}
