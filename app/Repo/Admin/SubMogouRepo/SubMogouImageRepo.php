<?php

namespace App\Repo\Admin\SubMogouRepo;

use App\Models\SubMogou;
use App\Models\SubMogouImage;
use Illuminate\Contracts\Database\Eloquent\Builder;

class SubMogouImageRepo
{
    public function getImages(SubMogou $subMogou,string $rotation_key): Builder{

        $mogouImageInstance = new SubMogouImage();

        $table = $mogouImageInstance->getPartition($rotation_key);

        $mogouImageInstance->setTable($table);

        return $mogouImageInstance->select("id","path","sub_mogou_id",'mogou_id',"page_number")
        ->where('sub_mogou_id',$subMogou->id)
        ->orderBy('page_number','asc');

    }
}
