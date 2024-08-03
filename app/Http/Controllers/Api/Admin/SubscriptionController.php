<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionActionRequest;
use App\Models\Subscription;
use App\Repo\Admin\Subscription\SubscriptionRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{

    public function __construct(protected SubscriptionRepo $subscriptionRepo)
    {

    }

    public function index(Request $request) : JsonResponse
    {
        $subscriptions = $this->subscriptionRepo->get($request);
        $total_user_subscription = $this->subscriptionRepo->total_user_subscription();
        return response()->json([
            'subscriptions' => $subscriptions,
            'total_user_subscription' => $total_user_subscription
        ]);
    }

    public function show($subscription)
    {
        $sub = $this->subscriptionRepo->getOne($subscription);

        return response()->json([
            'subscription' => $sub
        ]);
    }

    public function create(SubscriptionActionRequest $request) : JsonResponse
    {
        $subscription = $this->subscriptionRepo->create($request);
        return response()->json([
            'subscription' => $subscription,
            'message' => 'Subscription created successfully.'
        ], Response::HTTP_CREATED);
    }

    public function update(SubscriptionActionRequest $request,Subscription $subscription)  : JsonResponse
    {
        $updated_subscription = $this->subscriptionRepo->update($request, $subscription);
        return response()->json([
            'subscription' => $updated_subscription,
            'message' => 'Subscription updated successfully.'
        ],Response::HTTP_OK);
    }

    public function delete(Subscription $subscription)  : JsonResponse
    {
        $this->subscriptionRepo->delete($subscription);
        return response()->json([
            'message' => 'Subscription deleted successfully.'
        ],Response::HTTP_OK);
    }


}
