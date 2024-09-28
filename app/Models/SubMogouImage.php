<?php

namespace App\Models;

use App\Traits\DbPartition;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMogouImage extends Model
{
    use HasFactory,DbPartition,HydraMedia;

    protected $table = 'sub_mogou_images';

    protected string $partition_prefix = 'sub_mogou_images';

    protected string $baseTable = 'sub_mogou_images';

    protected static function boot()
    {
        parent::boot();

        static::dbConstructing();
    }

    protected $fillable = [
        'sub_mogou_id',
        'path',
        'page_number'
    ];

    public function subMogou()
    {
        return $this->belongsTo(SubMogou::class);
    }
}
