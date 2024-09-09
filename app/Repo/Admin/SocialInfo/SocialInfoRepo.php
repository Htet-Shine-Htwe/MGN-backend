<?php

namespace App\Repo\Admin\SocialInfo;

use App\Enum\SocialInfoType;
use App\Models\SocialInfo;
use HydraStorage\HydraStorage\Service\Option\MediaOption;
use HydraStorage\HydraStorage\Traits\HydraMedia;

class SocialInfoRepo
{

    use HydraMedia;

    protected $model;

    public function __construct()
    {
        $this->model = new SocialInfo();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function getBanners()
    {
        return $this->model->where('type', SocialInfoType::Banner->value)->get();
    }

    public function create($data)
    {
        if (isset($data['cover_photo'])) {
            $data['cover_photo'] = $this->storeMedia($data['cover_photo'], 'social_info');
        }

        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $socialInfo = $this->model->findOrfail($id);
        if (isset($data['cover_photo'])) {
            $data['cover_photo'] = $this->storeMedia($data['cover_photo'], 'social_info',false);
            $this->removeMedia('public/social_info/' . $socialInfo->cover_photo);
            $socialInfo->meta = null;
        }

        $socialInfo->update($data);

        return $socialInfo;
    }

    public function delete($id)
    {
        $socialInfo = $this->model->findOrfail($id);
        $this->removeMedia(storage_path('app/public/social_info/' . $socialInfo->cover_photo));
        return $socialInfo->delete();
    }


}
