<?php

namespace App\Repo\Admin;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $data = $request->all();

        isset($data['password']) ? $data['password'] = bcrypt($data['password']) : null;

        if($data['password'] == null) {
            unset($data['password']);
        }

        $user = User::where('id', $id)->firstOrFail();

        $data = self::updateDataSubscription($data, $user);

        $user->update($data);
        return $user;
    }

    protected static function mutateDataSubscription(mixed $data): mixed
    {
        if(isset($data['current_subscription_id'])) {
            $end_date = Subscription::where('id', $data['current_subscription_id'])->first()->duration;

            $data['subscription_end_date'] = now()->addDays($end_date);
        }
        return $data;
    }

    public static function updateDataSubscription(mixed $data,User $user): mixed
    {

        if(isset($data['current_subscription_id']) && $user->current_subscription_id != $data['current_subscription_id']) {
            $end_date = Subscription::where('id', $data['current_subscription_id'])->first()->duration;

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
