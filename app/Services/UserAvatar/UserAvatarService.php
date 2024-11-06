<?php

namespace App\Services\UserAvatar;

use App\Models\UserAvatar;
use HydraStorage\HydraStorage\Service\Option\MediaOption;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

/**
 * Class UserAvatarService
 *
 * This service class handles operations related to user avatars,
 * including retrieval, creation, updating, and deletion of avatars.
 *
 * @version 1.0.0
 * @company North Wolf
 * @developer Dede182
 */
class UserAvatarService
{

    use HydraMedia;
    /**
     * Retrieve all user avatars.
     *
     * @return Collection<int, UserAvatar>
     */
    public function getUserAvatars(): Collection
    {
        return UserAvatar::all();
    }

    /**
     * Retrieve a specific user avatar by its ID.
     *
     * @param string $id
     * @return UserAvatar
     */
    public function getUserAvatarById(string $id): UserAvatar|null
    {
        return UserAvatar::find($id);
    }

    /**
     * Create and store a new user avatar.
     *
     * @param  string $name
     * @param  UploadedFile $file
     * @return UserAvatar
     */
    public function createNewAvatar(string $name,UploadedFile $file): UserAvatar
    {
        $mediaOption = MediaOption::create()->setQuality(100)->get();

        $avatar = new UserAvatar();
        $avatar->avatar_name = $name;
        $avatar->avatar_path = $this->storeMedia($file, 'user_avatars', false, $mediaOption);
        $avatar->save();
        return $avatar;
    }

    /**
     * Update an existing user avatar.
     *
     * @param  string $id
     * @param  string $name
     * @param  UploadedFile $file
     * @return UserAvatar
     */
    public function updateUserAvatar(string $id,string $name,UploadedFile $file): UserAvatar
    {
        $mediaOption = MediaOption::create()->setQuality(100)->get();

        $avatar = UserAvatar::findOrFail($id);
        $this->removeMedia("public/user_avatars/$avatar->avatar_path");

        $avatar->avatar_name = $name;
        $avatar->avatar_path = $this->storeMedia($file, 'user_avatars', false, $mediaOption);


        $avatar->save();
        return $avatar;
    }

    /**
     * Delete a user avatar by its ID.
     *
     * @param string $id
     * @return bool
     */
    public function deleteUserAvatar(string $id): bool
    {
        $avatar = UserAvatar::findOrFail($id);
        $this->removeMedia("public/user_avatars/$avatar->avatar_path");
        $avatar->delete();

        return true;
    }

    /**
     * Bulk delete user avatars by their IDs.
     *
     * @param  array $ids
     * @return bool
     */
    public function bulkDeleteUserAvatars(array $ids): bool
    {
        $avatars = UserAvatar::whereIn('id', $ids)->get();
        foreach ($avatars as $avatar) {
            $this->removeMedia("public/user_avatars/$avatar->avatar_path");
            $avatar->delete();
        }
        return true;
    }
}
