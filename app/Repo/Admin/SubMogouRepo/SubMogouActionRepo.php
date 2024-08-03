<?php

namespace App\Repo\Admin\SubMogouRepo;

use App\Models\Mogou;
use App\Models\SubMogou;
use HydraStorage\HydraStorage\Traits\HydraMedia;

class SubMogouActionRepo
{
    use HydraMedia;

    public function __construct()
    {
    }

    protected function setSubMogouTable($id)
    {
        $mogou = Mogou::where('id',$id)->first();

        $rotation_key = $mogou->rotation_key;

        $sub_mogou = new SubMogou();
        $table = $sub_mogou->getPartition($rotation_key);

        $sub_mogou->setTable($table);

        $sub_mogou->setKeyName('id');

        return $sub_mogou;
    }

    public function generateSubMogouFolder($sub_mogou) :string
    {
        $folder = 'sub_mogou/'.$sub_mogou['slug']."/cover";

        return $folder;
    }

    public function saveNewDraft(array $data) :SubMogou
    {
        $sub_mogou = $this->setSubMogouTable($data['mogou_id']);

        $sub_mogou = $sub_mogou->create($data);

        return $sub_mogou;
    }

    public function updateCover(array $data) :SubMogou
    {
        $sub_mogou_model = $this->setSubMogouTable($data['mogou_id']);

        $sub_mogou = $sub_mogou_model->where('slug',$data['slug'])->firstOrFail();

        $store_cover_folder = generateStorageFolder("sub_mogou",$data['slug'].'/cover');

        $data['cover'] = $this->storeMedia($data['cover'], $store_cover_folder ,false);

        $sub_mogou->cover = $data['cover'];

        $sub_mogou->save();

        return $sub_mogou;
    }

    public function show($mogous_id, $sub_mogou_id) :SubMogou
    {
        $sub_mogou = $this->setSubMogouTable($mogous_id);

        $sub_mogou = $sub_mogou->where('id',$sub_mogou_id)->firstOrFail();

        return $sub_mogou;
    }
}
