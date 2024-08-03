<?php

namespace App\Models;

use App\Enum\MogouFinishStatus;
use App\Enum\MogousStatus;
use App\Enum\MogouTypeEnum;
use App\Scope\MogouScope;
use App\Services\Partition\TablePartition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Mogou extends Model
{
    use HasFactory,\Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use MogouScope;

    protected $fillable = [
        'rotation_key',
        'title',
        'slug',
        'description',
        'author',
        'cover',
        'status',
        'mogou_type',
        'finish_status',
        'legal_age',
        'rating',
        'released_year',
        'released_at',
    ];

    protected $casts = [
        'status' => MogousStatus::class,
        'released_at' => 'datetime',
        'rating' => 'double',
        'legal_age' => 'boolean',
        'finish_status' => MogouFinishStatus::class,
        'mogou_type' => MogouTypeEnum::class,
    ];

    protected $appends = ['status_name','mogou_type_name','finish_status_name'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($mogou){
            $mogou->slug = Str::slug($mogou->title);
            $mogou->rotation_key = TablePartition::getRandomRotationKey();
        });

        static::updating(function($mogou){
            $mogou->slug = Str::slug($mogou->title);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected function getStatusNameAttribute()
    {
        if($this->status)
        {
            return MogousStatus::getStatusName($this->status);
        }
    }

    protected function getMogouTypeNameAttribute()
    {
        if($this->mogou_type)
        {
            return MogouTypeEnum::getMogouTypeName($this->mogou_type);
        }
    }

    protected function getFinishStatusNameAttribute()
    {
        if($this->finish_status)
        {
            return MogouFinishStatus::getKey($this->finish_status);
        }
    }

    // relationship
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'mogous_categories');
    }

    public function subMogous($table_name="alpha")
    {
        $instance = new SubMogou();
        $instance->setTable($table_name."_sub_mogous");

        return $this->newHasMany(
            $instance->newQuery(),$this,$instance->getTable().'.mogou_id','id'
        );
    }

    public function getReleasedAtAttribute($value)
    {
        return date('d M,Y', strtotime($value));
    }

}
