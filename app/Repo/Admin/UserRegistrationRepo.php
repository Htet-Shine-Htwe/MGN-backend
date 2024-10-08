<?php

namespace App\Repo\Admin;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class UserRegistrationRepo
{
    public static function registerUser(UserRegistrationRequest $request) :User
    {

        $data =  $request->validated();
        $data = self::mutateDataSubscription($data);
        return User::create($data);
    }

    /**
     * list
     *
     * @param  Request $request
     * @return LengthAwarePaginator<User>
     */
    public function list(Request $request): LengthAwarePaginator
    {
        return User::search($request->search)
        ->expiredSubscription($request->expired)
        ->filter($request->filter)
        ->orderBy($request->order_by ?? 'id', $request->order ?? 'desc')
        ->paginate($request->limit ?? 10)
        ->withQueryString();
    }

    public function show(string $haystack,string $value): User
    {
        return User::where($haystack, $value)
        ->firstOrFail();
    }

    public function updateUser(UserRegistrationRequest $request,string $id) :User
    {
        $data = $request->validated();
        $user = User::where('id', $id)->firstOrFail();

        // $data = self::updateDataSubscription($data, $user);
        $user->update($data);
        return $user;
    }

    protected static function mutateDataSubscription(mixed $data): mixed
    {
        if(isset($data['current_subscription_id'])) {
            $end_date = Subscription::where('id', $data['current_subscription_id'])->first()->max;

            $data['subscription_end_date'] = now()->addDays($end_date);
        }
        return $data;
    }

    protected static function updateDataSubscription(mixed $data,User $user): mixed
    {

        if($user->current_subscription_id != $data['current_subscription_id']) {
            $end_date = Subscription::where('id', $data['current_subscription_id'])->first()->max;

            UserSubscription::create(
                [
                'user_id' => $user->id,
                'subscription_id' => $data['current_subscription_id'],
                ]
            );

            $data['subscription_end_date'] = now()->addDays($end_date);
        }

        return $data;

    }


}
