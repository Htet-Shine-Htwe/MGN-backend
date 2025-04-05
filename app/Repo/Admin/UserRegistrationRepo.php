<?php

namespace App\Repo\Admin;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRegistrationRepo
 *
 * This repository class handles operations related to user registration,
 *
 * @version 1.0.0
 * @company North Wolf
 * @developer Dede182
 */

class UserRegistrationRepo
{
    /**
     * handle user registration
     *
     * @param UserRegistrationRequest $request
     * @return User
     */
    public static function registerUser(UserRegistrationRequest $request): User
    {
        return DB::transaction(function () use ($request) {
            $data =  $request->validated();
            $data = self::mutateDataSubscription($data);
            $user = User::create($data);
            UserSubscription::create(
                [
                    'user_id' => $user->id,
                    'subscription_id' => $data['current_subscription_id'],
                ]
            );
            return $user;
        });
    }

    /**
     * List all users
     *
     * @param  Request $request
     * @return LengthAwarePaginator<User>
     */
    public function list(Request $request): LengthAwarePaginator
    {

        return User::search($request->search)
            ->expiredSubscription($request->expired)
            ->filterSubscription()
            ->filterActiveUser($request->active)
            ->orderBy($request->order_by ?? 'id', $request->order ?? 'desc')
            ->paginate($request->limit ?? 10)
            ->withQueryString();
    }

    /**
     * Retrieve a specific user by its ID.
     *
     * @param  string $haystack
     * @param  string $value
     * @return User
     */
    public function show(string $haystack, string $value): User
    {
        return User::where($haystack, $value)->firstOrFail();
    }

    /**
     * Update an existing user.
     *
     * @param  UserRegistrationRequest $request
     * @param  string $id
     * @return User
     */
    public function updateUser(UserRegistrationRequest $request, string $id): User
    {
        $data = $request->all();

        isset($data['password']) ? $data['password'] = bcrypt($data['password']) : null;

        if ($data['password'] == null) {
            unset($data['password']);
        }

        $user = User::where('id', $id)->firstOrFail();

        $data = self::updateDataSubscription($data, $user);

        $user->update($data);
        return $user;
    }

    /**
     * mutate data subscription of user
     *
     * @param  mixed $data
     * @return mixed
     */
    protected static function mutateDataSubscription(mixed $data): mixed
    {
        if (isset($data['current_subscription_id'])) {
            $end_date = Subscription::where('id', $data['current_subscription_id'])->first()->duration;

            $data['subscription_end_date'] = now()->addDays($end_date);
        }
        return $data;
    }

    /**
     *  update data subscription of user
     *
     * @param  mixed $data
     * @param  User $user
     * @return mixed
     */
    public static function updateDataSubscription(mixed $data, User $user): mixed
    {

        if (isset($data['current_subscription_id'])) {
            $end_date = Subscription::where('id', $data['current_subscription_id'])->first()->duration;

            if ($end_date == 0) {
                $end_date = 2500;
            }

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
