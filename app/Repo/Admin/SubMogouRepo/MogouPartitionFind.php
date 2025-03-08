<?php

namespace App\Repo\Admin\SubMogouRepo;

use App\Models\Mogou;
use App\Models\SubMogou;
use App\Models\SubMogouImage;

class MogouPartitionFind
{


    public function getSubMogouInstance(string $key="id",string $value =null): SubMogou
    {
        $mogou = (new Mogou)->where($key, $value)->firstOrFail();

        $sub_mogou = new SubMogou();
        $table = $sub_mogou->getPartition($mogou->rotation_key);

        $sub_mogou->setTable($table);

        $sub_mogou->setKeyName('id');
        return $sub_mogou;
    }


    public static function getSubMogou(string $key="id",string $value =null): SubMogou
    {
        $mogou = (new Mogou)->where($key, $value)->firstOrFail();

        $sub_mogou = new SubMogou();
        $table = $sub_mogou->getPartition($mogou->rotation_key);

        $sub_mogou->setTable($table);

        $sub_mogou->setKeyName('id');
        return $sub_mogou;
    }

    public static function getSubMogouImage(string $key="id",string $value =null): SubMogouImage
    {
        $mogou= Mogou::where($key, $value)->firstOrFail();

        $sub_mogou = new SubMogouImage;
        $table = $sub_mogou->getPartition($mogou->rotation_key);

        $sub_mogou->setTable($table);

        $sub_mogou->setKeyName('id');
        return $sub_mogou;
    }


}
