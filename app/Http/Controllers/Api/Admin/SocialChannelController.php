<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialChannelActionRequest;
use App\Repo\Admin\SocialChannel\SocialChannelActionRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialChannelController extends Controller
{
    public function __construct(protected SocialChannelActionRepo $socialChannelActionRepo)
    {
    }

    public function create(SocialChannelActionRequest $request): JsonResponse
    {
        $channel = $this->socialChannelActionRepo->create($request);

        return response()->json([
            'success' => true,
            'channel' => $channel,
        ]);
    }
}
