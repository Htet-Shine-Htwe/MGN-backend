<?php

namespace App\Models;

use App\Services\IpAddressService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterAnalysis extends Model
{
    use HasFactory;

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
