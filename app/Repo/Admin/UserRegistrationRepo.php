<?php

namespace App\Repo\Admin;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class UserRegistrationRepo
{
    public static function registerUser(UserRegistrationRequest $request) :User
    {
        $request->validate(
            [
            'email' => 'unique:users,email'
            ]
        );

        $data = $request->validated();
        $data = self::mutateDataSubscription($data);
        return User::create($data);
    }

    public function list(Request $request)
    {
        $users = User::search($request->search)
        ->expiredSubscription($request->expired)
        ->filter($request->filter)
        ->orderBy($request->order_by ?? 'id', $request->order ?? 'desc')
        ->paginate($request->limit ?? 10)
        ->withQueryString();

        return $users;
    }

    public function show(string $id)
    {
        return User::where('user_code', $id)
        ->firstOrFail();
    }

    public function updateUser(UserRegistrationRequest $request,string $id) :User
    {

        $request->validate(
            [
            'email' => 'unique:users,email,'.$id
            ]
        );

        $data = $request->validated();
        $user = User::where('user_code', $id)->firstOrFail();

        UserSubscription::create(
            [
            'user_id' => $user->id,
            'subscription_id' => $data['current_subscription_id'],
            ]
        );


        $data = self::updateDataSubscription($data, $user);
        $user->update($data);
        return $user;
    }

    protected static function mutateDataSubscription($data)
    {
        if(isset($data['current_subscription_id'])) {
            $end_date = Subscription::where('id', $data['current_subscription_id'])->first()->max;



            $data['subscription_end_date'] = now()->addDays($end_date);
        }
        return $data;
    }

    protected static function updateDataSubscription($data,$user)
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
