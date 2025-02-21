<?php

namespace App\Repo\Admin\SubMogouRepo;

use App\Models\Mogou;
use App\Models\SubMogou;
use App\Services\LexoRank\LexoRankHelperService;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubMogouActionRepo
{
    use HydraMedia;

    public function __construct() {}
    /**
     * generateSubMogouFolder
     *
     * @param array $sub_mogou
     * @return string
     */
    public function generateSubMogouFolder(array $sub_mogou): string
    {
        return 'sub_mogou/' . $sub_mogou['slug'] . "/cover";
    }

    /**
     * saveNewDraft
     *
     * @param array $data
     * @return SubMogou
     */
    public function saveNewDraft(array $data): SubMogou | array
    {
        $sub_mogou = MogouPartitionFind::getSubMogou("slug", $data['mogou_slug']);

        $parent_mogou = MogouPartitionFind::$parentMogou;

        $chapter_number = $sub_mogou->where('mogou_id', $parent_mogou->id)->where('chapter_number', $data['chapter_number'])->first();

        if ($chapter_number) {
            throw new \Exception("Chapter number already exists");
        }

        $data['mogou_id'] = $parent_mogou->id;
        $data['creator_id'] = auth()->id();
        $data['creator_type'] = get_class(auth()->user());

        return $sub_mogou->create($data);
    }

    /**
     * updateInfo
     *
     * @param array $data
     * @return SubMogou
     */
    public function updateInfo(array $data): SubMogou
    {
        $sub_mogou =  MogouPartitionFind::getSubMogou("slug", $data['mogou_slug']);

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
    public function getLatestChapterNumber(string $slug): int
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
    public function updateCover(array $data): SubMogou
    {
        // $sub_mogou_model =  MogouPartitionFind::getSubMogou("slug", $data['slug']);
        $sub_mogou_model =  MogouPartitionFind::getSubMogou("id", $data['id']);
        $sub_mogou = $sub_mogou_model->where('slug', $data['slug'])->firstOrFail();
        $store_cover_folder = generateStorageFolder("sub_mogou", $data['slug'] . '/cover');

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
    public function show(string $mogou_slug, string $sub_mogou_id): array
    {
        $sub_mogou =  MogouPartitionFind::getSubMogou("slug", $mogou_slug);

        $sub_mogou = $sub_mogou->where('id', $sub_mogou_id)->firstOrFail();
        $sub_mogou['images'] = (new SubMogouImageRepo)->getImages($sub_mogou, MogouPartitionFind::$parentMogou->rotation_key)->get();

        $subMogouImage = MogouPartitionFind::getSubMogouImage("id", $sub_mogou['mogou_id']);

        $plucked = $sub_mogou['images']->pluck('position');

        $duplicates = $plucked->duplicates();
        if ($duplicates->count() > 0) {
            LexoRankHelperService::resetLexoRanks($subMogouImage,$sub_mogou_id);
        }

        return [
            'sub_mogou' => $sub_mogou,

        ];
    }

    /**
     * getChaptersQuery
     *
     * @param  string $mogou_slug
     * @return Builder<SubMogou> | SubMogou
     */
    public function getChaptersQuery(string $mogou_slug): Builder | SubMogou
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
            ->addSelect(DB::raw("(SELECT COUNT(*) FROM " . $rotation_key . "_sub_mogou_images WHERE " . $rotation_key . "_sub_mogou_images.sub_mogou_id = " . $rotation_key . "_sub_mogous.id) as images_count"))
            ->orderBy('chapter_number', 'asc')
            ->where('mogou_id', $mogou->id);
    }

    // LexoRankHelperService::resetLexoRanks($model,228);

    public function updateImageIndex(array $data): bool
    {
        $subMogou = MogouPartitionFind::getSubMogou("id", $data['mogou_id'])->where('id', $data['sub_mogou_id'])->firstOrFail();

        $subMogouImage = MogouPartitionFind::getSubMogouImage("id", $data['mogou_id']);

        $sourceImage = $subMogouImage->where('sub_mogou_id', $subMogou->id)->where("id", $data['source_image_id'])->firstOrFail();
        $targetImage = $subMogouImage->where('sub_mogou_id', $subMogou->id)->where('id', $data['target_image_id'])->firstOrFail();


        $isBeforeOrAfter = strcmp($sourceImage->position, $targetImage->position) < 0;


        Log::info('before-order', [
            'source' => $sourceImage->position,
            'target' => $targetImage->position,
            'isBeforeOrAfter' => $isBeforeOrAfter ? 'before' : 'after'
        ]);

        if ($isBeforeOrAfter) {
            // Ensure unique position before moving
            if ($subMogouImage->where('position', $sourceImage->position)->count() > 1) {
                LexoRankHelperService::resetLexoRanks($subMogouImage, $data['mogou_id']);
                $sourceImage->position = $this->generateNewLexoRank($targetImage->position);
                $sourceImage->save();
            }
            $sourceImage->moveAfter($targetImage);
        } else {
            if ($subMogouImage->where('position', $sourceImage->position)->count() > 1) {
                LexoRankHelperService::resetLexoRanks($subMogouImage, $data['mogou_id']);
                $sourceImage->position = $this->generateNewLexoRank($targetImage->position);
                $sourceImage->save();
            }
            $sourceImage->moveBefore($targetImage);
        }


        Log::info('after-order', [
            'source' => $sourceImage->position,
            'target' => $targetImage->position,
            'isBeforeOrAfter' => $isBeforeOrAfter ? 'before' : 'after'
        ]);


        return true;
    }

    private function generateNewLexoRank(string $targetPosition) : string
    {
        return $targetPosition . "a"; // Append a character to ensure uniqueness
    }
}
