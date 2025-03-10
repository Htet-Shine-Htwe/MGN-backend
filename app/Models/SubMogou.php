<?php

namespace App\Models;

use App\Traits\DbPartition;
use Database\Factories\SubMogouFactory;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;


class SubMogou extends Model
{
    /** @use HasFactory<SubMogouFactory> */
    use HasFactory,DbPartition,HydraMedia;

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
        'creator_id',
        'creator_type',
    ];

    protected $hidden = [
        'creator_id',
        'creator_type',
    ];

    protected $casts = [
        'third_party_redirect' => 'boolean',
    ];

    // protected $appends = ['full_cover_path'];

    protected static function boot()
    {
        parent::boot();

        static::dbConstructing();

        static::creating(
            function ($sub_mogou) {
                $sub_mogou->slug = Str::slug($sub_mogou->title);
                Mogou::where('id', $sub_mogou->mogou_id)->increment('total_chapters');

            }
        );

        static::updating(
            function ($sub_mogou) {
                $sub_mogou->slug = Str::slug($sub_mogou->title);
            }
        );

        static::deleting(
            function ($sub_mogou) {
                Mogou::where('id', $sub_mogou->mogou_id)->decrement('total_chapters');
            }
        );
    }

    public function getFullCoverPathAttribute(): string
    {
        // return asset('storage/'.generateStorageFolder("sub_mogou",$this->slug.'/cover') . '/' . $this->cover);
        return $this->getMedia(generateStorageFolder("sub_mogou", $this->slug.'/cover') . '/' . $this->cover);
    }

    public function getCreatedAtAttribute(string $value): string
    {

        return date('d M,Y', strtotime($value));
    }


    public function getSubscriptionCollectionAttribute(?string $value): array
    {
        if (empty($value)) {
            return [];
        }
        return json_decode($value, true);
    }

    /**
     * mogou
     *
     * @return BelongsTo<Mogou, $this>
     */
    public function mogou(): BelongsTo
    {
        return $this->belongsTo(Mogou::class);
    }


    /**
     * images
     *
     * @param string $table_name
     * @return HasMany<SubMogouImage, $this>
     */
    public function images(string $table_name="alpha"): HasMany
    {
        $instance = new SubMogouImage;
        $instance->setTable("{$table_name}_sub_mogou_images");

        return $this->newHasMany(
           ( new $instance)->query(), $this,'sub_mogou_id', 'id'
        );
    }

    /**
     * creator
     *
     * @return MorphTo<Model, $this>
     */
    public function creator() : MorphTo
    {
        return $this->morphTo();
    }
}
