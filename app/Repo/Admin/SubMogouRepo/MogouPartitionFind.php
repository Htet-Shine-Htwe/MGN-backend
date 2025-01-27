<?php

namespace App\Repo\Admin\SubMogouRepo;

use App\Models\Mogou;
use App\Models\SubMogou;

class MogouPartitionFind
{
    public static Mogou $parentMogou;

    public static string $rotation_key;

    public static function getSubMogou(string $key="id",string $value =null): SubMogou
    {
        self::$parentMogou = Mogou::where($key, $value)->firstOrFail();

        self::$rotation_key = self::$parentMogou->rotation_key;

        $sub_mogou = new SubMogou();
        $table = $sub_mogou->getPartition(self::$rotation_key);

        $sub_mogou->setTable($table);

        $sub_mogou->setKeyName('id');
        return $sub_mogou;
    }


}
