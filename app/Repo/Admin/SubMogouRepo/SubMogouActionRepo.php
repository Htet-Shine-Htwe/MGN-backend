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

    public function __construct()
    {
    }
    /**
     * generateSubMogouFolder
     *
     * @param  SubMogou $sub_mogou
     * @return string
     */
    public function generateSubMogouFolder(SubMogou $sub_mogou) :string
    {
        return 'sub_mogou/'.$sub_mogou['slug']."/cover";
    }

    /**
     * saveNewDraft
     *
     * @param array $data
     * @return SubMogou
     */
    public function saveNewDraft(array $data) :SubMogou | array
    {
        $sub_mogou = MogouPartitionFind::getSubMogou("slug", $data['mogou_slug']);

        $parent_mogou = MogouPartitionFind::$parentMogou;

        $chapter_number = $sub_mogou->where('mogou_id', $parent_mogou->id)->where('chapter_number', $data['chapter_number'])->first();

        if ($chapter_number) {
            throw new \Exception("Chapter number already exists");
        }

        $data['mogou_id'] = $parent_mogou->id;

        return $sub_mogou->create($data);
    }

    /**
     * updateInfo
     *
     * @param array $data
     * @return SubMogou
     */
    public function updateInfo(array $data) :SubMogou
    {
        $sub_mogou =  MogouPartitionFind::getSubMogou("slug", $data['mogou_slug']);

        $parent_mogou = MogouPartitionFind::$parentMogou;

        $chapter_number = $sub_mogou
        ->where('mogou_id', $parent_mogou->id)
        ->where('chapter_number', $data['chapter_number'])->first();

        if ($chapter_number && $chapter_number->id != $data['id']) {
            throw new \Exception("Chapter number already exists");
        }

        $sub_mogou = $sub_mogou->where('id', $data['id'])->firstOrFail();
        $sub_mogou->update($data);
        return $sub_mogou;
    }

    /**
     * getLatestChapterNumber
     *
     * @param string $slug
     * @return int
     */
    public function getLatestChapterNumber(string $slug) : int
    {
        $sub_mogou =  MogouPartitionFind::getSubMogou("slug", $slug);
        return $sub_mogou->where('mogou_id', MogouPartitionFind::$parentMogou->id)->max('chapter_number') ?? 0;
    }

    /**
     * updateCover
     *
     * @param array $data
     * @return SubMogou
     */
    public function updateCover(array $data) :SubMogou
    {
        $sub_mogou_model =  MogouPartitionFind::getSubMogou("slug", $data['slug']);
        $sub_mogou = $sub_mogou_model->where('slug', $data['slug'])->firstOrFail();
        $store_cover_folder = generateStorageFolder("sub_mogou", $data['slug'].'/cover');

        $data['cover'] = $this->storeMedia($data['cover'], $store_cover_folder, false);
        $sub_mogou->cover = (string) $data['cover'];
        $sub_mogou->save();
        return $sub_mogou;
    }

    /**
     * show
     *
     * @param string $mogou_slug
     * @param string $sub_mogou_id
     * @return array
     */
    public function show(string $mogou_slug,string $sub_mogou_id) : array
    {
        $sub_mogou =  MogouPartitionFind::getSubMogou("slug", $mogou_slug);
        $sub_mogou = $sub_mogou->where('id', $sub_mogou_id)->firstOrFail();
        $sub_mogou['images'] = (new SubMogouImageRepo)->getImages($sub_mogou, MogouPartitionFind::$parentMogou->rotation_key)->get();

        return [
            'sub_mogou' => $sub_mogou,

        ];
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
