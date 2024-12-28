<?php

namespace App\Models;

use App\Enum\MogouFinishStatus;
use App\Enum\MogousStatus;
use App\Enum\MogouTypeEnum;
use App\Scope\MogouScope;
use App\Services\Partition\TablePartition;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Mews\Purifier\Casts\CleanHtml;

class Mogou extends Model
{
    use HasFactory,\Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use MogouScope;
    use HydraMedia;

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
        'total_chapters',
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

    protected static function boot(): void
    {
        parent::boot();

        static::creating(
            function ($mogou): void {
                $mogou->slug = Str::slug($mogou->title);
                $mogou->rotation_key = TablePartition::getRandomRotationKey();
            }
        );

        static::updating(
            function ($mogou) {
                $mogou->slug = Str::slug($mogou->title);
            }
        );
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function getStatusNameAttribute(): string
    {
        return  $this->status ? MogousStatus::getStatusName($this->status) : "";
    }

    protected function getCoverAttribute(string $value): string
    {
        return $this->getMedia($value, 'public/mogou/cover');
    }

    protected function getMogouTypeNameAttribute(): string
    {

        return $this->mogou_type ?  MogouTypeEnum::getMogouTypeName($this->mogou_type) : "";

    }

    protected function getFinishStatusNameAttribute(): string
    {
        return $this->finish_status ? MogouFinishStatus::getKey($this->finish_status) : "";

    }

    public function getTotalViewCountAttribute(): int
    {
        return (int) $this->subMogous()->sum('views');
    }

    // relationship

    /**
     * categories
     *
     * @return BelongsToMany<Category>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'mogous_categories');
    }

    /**
     * subMogous
     *
     * @param string $table_name
     * @return HasMany<SubMogou>
     */
    public function subMogous(string $table_name="alpha"): HasMany
    {
        $instance = new SubMogou();
        $instance->setTable($table_name."_sub_mogous");

        return $this->newHasMany(
            $instance->newQuery(), $this, $instance->getTable().'.mogou_id', 'id'
        );
    }


    public function getReleasedAtAttribute(?string $value): string
    {
        return date('d M,Y', strtotime($value));
    }

    public function getCreatedAtAttribute(?string $value): string
    {
        return date('d M,Y', strtotime($value));
    }

}
