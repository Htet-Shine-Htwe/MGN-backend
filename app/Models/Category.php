<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    protected $fillable = ['title','slug'];

    public $timestamps = false;

    protected $hidden = ['pivot'];

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($category) {
                $category->slug = Str::slug($category->title);
            }
        );

        static::updating(
            function ($category) {
                $category->slug = Str::slug($category->title);
            }
        );
    }

    /**
     * scopeSearch
     *
     * @param Builder<Category> $query
     * @return Builder<Category>
     */
    public function scopeSearch($query,string|null $search): Builder
    {
        return $query->where('title', 'like', '%'.$search.'%');
    }

    /**
     * mogous
     *
     * @return BelongsToMany<Mogou,$this>
     */
    public function mogous(): BelongsToMany
    {
        return $this->belongsToMany(Mogou::class, 'mogous_categories');
    }

    /**
     * scopeWithMogousCount
     *
     * @param  Builder<Category> $query
     * @return Builder<Category>
     */
    public function scopeWithMogousCount($query) : Builder
    {
        return $query->when(
            request('with_mogous_count'), function ($query) {
                return $query->withCount('mogous');
            }
        );
    }

    /**
     * scopeOrderByMogousCount
     *
     * @param  Builder<Category> $query
     * @return Builder<Category>
     */
    public function scopeOrderByMogousCount($query)
    {
        return $query->when(
            request('order_by_mogous_count'), function ($query) {
                return $query->withCount('mogous')->orderBy('mogous_count', request('order_by_mogous_count'))
                    ->orderBy('id', 'desc');
            }
        );
    }

}
