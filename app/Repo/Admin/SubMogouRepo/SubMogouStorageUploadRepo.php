<?php

namespace App\Repo\Admin\SubMogouRepo;

use App\Http\Requests\SubMogouStorageUploadRequest;
use App\Models\ApplicationConfig;
use App\Models\Mogou;
use App\Models\SubMogou;
use App\Models\SubMogouImage;
use HydraStorage\HydraStorage\Service\Option\MediaOption;
use HydraStorage\HydraStorage\Traits\HydraMedia;

class SubMogouStorageUploadRepo
{

    use HydraMedia;

    protected Mogou $parentMogou;

    protected int $compress_quality ;

    public function __construct()
    {
        $this->compress_quality = config('hydrastorage.compressed_quality') ?? 100;
    }

    protected function setSubMogouTable(string $key="id",string $value =null): SubMogou
    {
        $this->parentMogou = Mogou::where($key, $value)->firstOrFail();

        $rotation_key = $this->parentMogou->rotation_key;

        $sub_mogou = new SubMogou();
        $table = $sub_mogou->getPartition($rotation_key);

        $sub_mogou->setTable($table);

        $sub_mogou->setKeyName('id');
        return $sub_mogou;
    }



    public function upload(SubMogouStorageUploadRequest $request): SubMogou{

        $subMogou = $this->setSubMogouTable("id", $request['mogou_id']);
        $subMogou = $subMogou->where('slug', $request['sub_mogou_slug'])->firstOrFail();

        $parent_mogou = $this->parentMogou->id;
        $sub_mogou_id = $subMogou->id;
        $path = "mogou/{$parent_mogou}/{$sub_mogou_id}";

        $mediaOption =  MediaOption::create()
        ->setQuality($this->compress_quality );

        if($request->has('water_mark')){
            $mediaOption = $mediaOption->setWaterMark($this->getWaterMarkImage(),'center',100);
        }
        $mediaOption = $mediaOption->get();
        $subMogouImage = new SubMogouImage();

        $rotation_key = $this->parentMogou->rotation_key;
        $table = $subMogouImage->getPartition($rotation_key);
        $subMogouImage->setTable($table);

        foreach ($request->upload_files as $file) {
            $obj['path'] = $this->storeMedia($file['file'], $path, true, $mediaOption,);
            $obj['sub_mogou_id'] = $subMogou->id;
            $obj['mogou_id'] = $parent_mogou;

            (clone $subMogouImage)->create($obj);
        }

        return $subMogou;
    }

    public function removeStorageFile(array $data): void
    {
        $subMogou = $this->setSubMogouTable("id", $data['mogou_id']);
        $subMogou = $subMogou->where('id', $data['sub_mogou_id'])->firstOrFail();

        $subMogouImage = new SubMogouImage();
        $rotation_key = $this->parentMogou->rotation_key;
        $table = $subMogouImage->getPartition($rotation_key);
        $subMogouImage->setTable($table);
        $fileRecord =  $subMogouImage->where('id', $data['image_id'])->firstOrFail();

        $this->removeMedia($fileRecord->path, "mogou/{$data['mogou_id']}/{$data['sub_mogou_id']}");


    }

    public function getWaterMarkImage(): string
    {
        $app = ApplicationConfig::firstOrFail();

        return $app->water_mark;
    }
}
