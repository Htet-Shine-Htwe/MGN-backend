<?php

namespace App\Models;

use App\Traits\DbPartition;
use Dede\Lexorank\LexoRankTrait;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class SubMogouImage extends Model
{
    use HasFactory,DbPartition,HydraMedia,LexoRankTrait;

    protected $table = 'sub_mogou_images';

    protected string $partition_prefix = 'sub_mogou_images';

    protected static string $sortableField = 'position';

    protected string $baseTable = 'sub_mogou_images';


    /**
     * applySortableQuery
     *
     * @param  Builder<static> $query
     * @param  SubMogouImage $model
     * @return Builder<static>
     */

    protected static function applySortableQuery(Builder $query,SubMogouImage $model) : Builder
    {
        $query->where("mogou_id", $model->mogou_id)
                ->where("sub_mogou_id", $model->sub_mogou_id);

        return $query;
    }


    protected static function boot(): void
    {
        parent::boot();

        static::dbConstructing();
    }

    protected $fillable = [
        'mogou_id',
        'sub_mogou_id',
        'path',
        'position',
    ];

    public function getPathAttribute(string $value): string
    {
        return $this->getMedia($value,"mogou/$this->mogou_id/$this->sub_mogou_id");
    }

    /**
     * subMogou
     *
     * @return BelongsTo<SubMogou, SubMogouImage>
     */
    public function subMogou(): BelongsTo
    {
        return $this->belongsTo(SubMogou::class);
    }
}
