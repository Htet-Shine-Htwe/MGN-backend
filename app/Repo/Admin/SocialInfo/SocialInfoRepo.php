<?php

namespace App\Repo\Admin\SocialInfo;

use App\Enum\SocialInfoType;
use App\Models\SocialInfo;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Collection;

class SocialInfoRepo
{

    use HydraMedia;

    protected SocialInfo $model;

    public function __construct()
    {
        $this->model = new SocialInfo();
    }

    /**
     * all
     *
     * @return Collection<int,SocialInfo>
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * getBanners
     *
     * @return Collection<int,SocialInfo>
     */
    public function getBanners(): Collection
    {
        return $this->model->where('type', SocialInfoType::Banner->value)->get();
    }

    public function create(array $data): SocialInfo
    {
        if (isset($data['cover_photo'])) {
            $data['cover_photo'] = $this->storeMedia($data['cover_photo'], 'social_info');
        }

        return $this->model->create($data);
    }

    public function update(string $id,array $data): SocialInfo
    {
        $socialInfo = $this->model->findOrfail($id);
        if (isset($data['cover_photo'])) {
            $data['cover_photo'] = $this->storeMedia($data['cover_photo'], 'social_info', false);
            $this->removeMedia('public/social_info/' . $socialInfo->cover_photo);
            $socialInfo->text_url = null;
        }

        $socialInfo->update($data);
        $socialInfo->refresh();

        return $socialInfo;
    }

    public function delete(string $id): bool
    {
        $socialInfo = $this->model->findOrfail($id);
        $this->removeMedia('public/social_info/' . $socialInfo->cover_photo);

        return $socialInfo->delete();
    }


}
