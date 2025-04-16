<?php

namespace App\Services\General;

use App\Enum\SocialInfoType;
use App\Models\SocialInfo;

class UserGeneralService
{
    public function contactUsSocialLink() : array
    {
        return SocialInfo::select("id","name","icon","redirect_url",'type')
        ->where("type",SocialInfoType::ReferSocial->value)
        ->limit(2)
        ->get()->toArray();
    }
}
