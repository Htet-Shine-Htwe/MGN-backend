<?php

namespace App\Models;

use App\Contracts\DbPartitionModelInterface;
use App\Traits\DbPartition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class SubMogou extends Model
{
    use HasFactory,\Staudenmeir\EloquentEagerLimit\HasEagerLimit,DbPartition;

    protected $table = 'sub_mogous';

    protected string $partition_prefix = 'sub_mogous';

    protected string $baseTable = 'sub_mogous';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover',
        'status',
        'chapter_number',
        'views',
        'third_party_url',
        'third_party_redirect',
        'subscription_only',
        'subscription_collection',
        'mogou_id',
    ];

    protected $appends = ['full_cover_path'];

    protected static function boot()
    {
        parent::boot();

        static::dbConstructing();

        static::creating(function($sub_mogou){
            $sub_mogou->slug = Str::slug($sub_mogou->title);
        });

        static::updating(function($sub_mogou){
            $sub_mogou->slug = Str::slug($sub_mogou->title);
        });
    }

    public function getFullCoverPathAttribute()
    {
        return asset('storage/'.generateStorageFolder("sub_mogou",$this->slug.'/cover') . '/' . $this->cover);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d M,Y', strtotime($value));
    }


    public function getSubscriptionCollectionAttribute($value)
    {
        return json_decode($value, true);
    }

    public function mogou()
    {
        return $this->belongsTo(Mogou::class);
    }


    public function mogou_images()
    {
        return $this->hasMany(SubMogouImage::class);
    }

    public function newRelatedInstance($class)
    {
        $instance = new $class;


        if ($this->relationLoaded('mogou')) {
            $mogou = $this->getRelation('mogou');
            $rotationKey = $mogou->title;

            if ($rotationKey == 'alpha') {
                $instance->setTable('alpha_sub_mogous');
            } elseif ($rotationKey == 'beta') {
                $instance->setTable('beta_sub_mogous');
            }
        }

        return $instance;
    }

    // when this model is loaded, it will check the parent model and set the table name accordingly

    // public function newQuery()
    // {

    //     $query = parent::newQuery();

    //     // current loaded model

    //         $rotationKey = $this->mogou()->first()->title;

    //         // if ($rotationKey == 'alpha') {
    //         //    $this->setTable('alpha_sub_mogous');
    //         // } elseif ($rotationKey == 'beta') {
    //         //     $this->setTable('beta_sub_mogous');
    //         // }

    //     if


    //     return $query;
    // }

}
