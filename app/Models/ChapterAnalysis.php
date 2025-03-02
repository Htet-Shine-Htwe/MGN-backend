<?php

namespace App\Models;

use App\Services\IpAddressService;
use Database\Factories\ChapterAnalysisFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterAnalysis extends Model
{
    /** @use HasFactory<ChapterAnalysisFactory> */
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'sub_mogou_id',
        'mogou_id',
        'ip',
        'date',
        'user_id'
    ];


    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->date = now();
        });
    }

    /**
     * mogou
     *
     * @return BelongsTo<Mogou,$this>
     */
    public function mogou() : BelongsTo
    {
        return $this->belongsTo(Mogou::class);
    }

    /**
     * subMogou
     *
     * @return BelongsTo<SubMogou,$this>
     */
    public function subMogou() : BelongsTo
    {
        return $this->belongsTo(SubMogou::class);
    }

    // public function setIpAttribute($value)
    // {
    //     $this->attributes['ip'] = app(IpAddressService::class)->pack($value);
    // }

    // public function getIpAttribute($value)
    // {
    //     if (is_resource($value)) {
    //         $value = stream_get_contents($value);
    //     }
    //     return app(IpAddressService::class)->unpack($value);
    // }
}
