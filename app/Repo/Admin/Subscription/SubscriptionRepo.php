<?php
namespace App\Repo\Admin\Subscription;


use App\Contracts\ModelRepoInterface;
use App\Http\Requests\SubscriptionActionRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionRepo implements ModelRepoInterface
{

    protected Request $request;

    public function get(Request $request) : mixed
    {
        $this->request = $request;
        return $this->collection();
    }

    public function getOne($subscription)
    {
        return Subscription::where('id', $subscription)->firstOrFail();
    }

    public function collection() : mixed
    {
        return Subscription::search($this->request->search)
        ->withCount('users')
        ->countBy($this->request->count_by)
        ->priceBy($this->request->price_by)
        ->paginate($this->request->limit ?? 10)
        ->withQueryString();
    }

    public function total_user_subscription() : int
    {
        return Subscription::withCount('users')->get()->sum('users_count');
    }

    public function create(SubscriptionActionRequest $request) : Subscription
    {
        $request->validate(
            [
            'title' => 'unique:subscriptions,title'
            ]
        );

        return Subscription::create($request->validated());
    }

    public function update(SubscriptionActionRequest $request, Subscription $subscription) : Subscription
    {
        $request->validate(
            [
            'title' => 'unique:subscriptions,title,'.$subscription->id
            ]
        );
        $subscription->update($request->validated());
        return $subscription;
    }

    public function delete(Subscription $subscription) : bool
    {
        return $subscription->delete();
    }

}
