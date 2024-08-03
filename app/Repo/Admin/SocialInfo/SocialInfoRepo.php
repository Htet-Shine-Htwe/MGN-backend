<?php

namespace App\Repo\Admin\SocialInfo;

use App\Models\SocialInfo;
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
            $data['cover_photo'] = $this->storeMedia($data['cover_photo'], 'social_info');
            $this->removeMedia(storage_path('app/public/social_info/' . $socialInfo->cover_photo));
        }

        return $socialInfo->update($data);
    }

    public function delete($id)
    {
        $socialInfo = $this->model->findOrfail($id);
        $this->removeMedia(storage_path('app/public/social_info/' . $socialInfo->cover_photo));
        return $socialInfo->delete();
    }


}
