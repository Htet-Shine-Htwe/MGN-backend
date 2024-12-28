<?php

namespace App\Models;

use App\Traits\DbPartition;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubMogouImage extends Model
{
    use HasFactory,DbPartition,HydraMedia;

    protected $table = 'sub_mogou_images';

    protected string $partition_prefix = 'sub_mogou_images';

    protected string $baseTable = 'sub_mogou_images';

    protected static function boot(): void
    {
        parent::boot();

        static::dbConstructing();
    }

    protected $fillable = [
        'mogou_id',
        'sub_mogou_id',
        'path',
        'page_number'
    ];

    public function getPathAttribute(string $value): string
    {
        return $this->getMedia($value,"public/mogou/$this->mogou_id/$this->sub_mogou_id");
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
