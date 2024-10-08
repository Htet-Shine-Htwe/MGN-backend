<?php

namespace App\Repo\Admin\SubMogouRepo;

use App\Models\Mogou;
use App\Models\SubMogou;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SubMogouActionRepo
{
    use HydraMedia;

    protected Mogou $parentMogou;

    public function __construct()
    {
    }

    protected function setSubMogouTable(string $key="id",string $value =null): SubMogou
    {
        $this->parentMogou = Mogou::where($key, $value)->first();

        $rotation_key = $this->parentMogou->rotation_key;

        $sub_mogou = new SubMogou();
        $table = $sub_mogou->getPartition($rotation_key);

        $sub_mogou->setTable($table);

        $sub_mogou->setKeyName('id');
        return $sub_mogou;
    }

    public function generateSubMogouFolder(SubMogou $sub_mogou) :string
    {
        return 'sub_mogou/'.$sub_mogou['slug']."/cover";
    }

    public function saveNewDraft(array $data) :SubMogou | array
    {
        $sub_mogou = $this->setSubMogouTable("slug", $data['mogou_slug']);
        $data['mogou_id'] = $this->parentMogou->id;
        return $sub_mogou->create($data);
    }

    public function updateCover(array $data) :SubMogou
    {
        $sub_mogou_model = $this->setSubMogouTable("slug", $data['slug']);

        $sub_mogou = $sub_mogou_model->where('slug', $data['slug'])->firstOrFail();

        $store_cover_folder = generateStorageFolder("sub_mogou", $data['slug'].'/cover');

        $data['cover'] = $this->storeMedia($data['cover'], $store_cover_folder, false);

        $sub_mogou->cover = $data['cover'];

        $sub_mogou->save();

        return $sub_mogou;
    }

    public function show(string $mogous_id,string $sub_mogou_id) :SubMogou
    {
        $sub_mogou = $this->setSubMogouTable("id", $mogous_id);

        $sub_mogou = $sub_mogou->where('id', $sub_mogou_id)->firstOrFail();

        return $sub_mogou;
    }

    /**
     * getChaptersQuery
     *
     * @param  string $mogou_slug
     * @return Builder<SubMogou>
     */
    public function getChaptersQuery(string $mogou_slug): Builder
    {
        // enable to trace the query
        $mogou = Mogou::where('slug', $mogou_slug)->first();

        $rotation_key = $mogou->rotation_key;

        $sub_mogou = new SubMogou();
        $table = $sub_mogou->getPartition($rotation_key);

        $sub_mogou->setTable($table);

        $sub_mogou->setKeyName('id');

        return $sub_mogou
            ->select("id", "title", "slug", "cover", "created_at", 'chapter_number', 'views', 'subscription_only')
            ->addSelect(DB::raw("(SELECT COUNT(*) FROM ".$rotation_key."_sub_mogou_images WHERE ".$rotation_key."_sub_mogou_images.sub_mogou_id = ".$rotation_key."_sub_mogous.id) as images_count"))
            ->where('mogou_id', $mogou->id);
    }
}
