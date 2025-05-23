<?php

namespace App\Scope;

use App\Enum\MogouFinishStatus;
use App\Enum\MogouTypeEnum;
use App\Models\Mogou;
use App\Services\Mogou\MogouService;
use Illuminate\Database\Eloquent\Builder;

trait MogouScope
{
    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */
    public function scopeOrderByRating(Builder $query): Builder
    {
        return $query->when(
            request('order_by_rating'),
            function (Builder $query): Builder {
                return $query->orderBy('rating', request('order_by_rating'));
            }
        );
    }


    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */    public function scopeFilterStatus(Builder $query, bool $orWhere = true): Builder
    {
        $status = request()->input('status');
        return $query->when(
            $status,
            function (Builder $query) use ($orWhere, $status): Builder {
                return $orWhere ? $query->orWhere('status', $status) : $query->where('status', $status);
            }
        );
    }


    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */
    public function scopeLegalOnly(Builder $query): Builder
    {
        return $query->when(
            request('legal_only'),
            function (Builder $query): Builder {
                if (request('legal_only') == 'false') {
                    return $query;
                } else {
                    return $query->where('legal_age', 0);
                }
            }
        );
    }

    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */
    public function scopeByFinishStatus(Builder $query): Builder
    {
        $finishStatus = request()->input('finish_status');

        return $query->when(
            $finishStatus,
            function (Builder $query) use ($finishStatus): Builder {
                if (is_string($finishStatus) && strpos($finishStatus, ',') !== false) {
                    $finishStatus = explode(',', $finishStatus);

                    foreach ($finishStatus as $key => $value) {
                        $finishStatus[$key] = MogouFinishStatus::getFinishStatus($value);
                    }

                    return $query->whereIn('finish_status', $finishStatus);
                } else {
                    return $query->where('finish_status', MogouFinishStatus::getFinishStatus($finishStatus));
                }
            }
        );
    }

    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */
    public function scopeByMogouType(Builder $query): Builder
    {
        $mogouType = request()->input('mogou_type') ?? request()->input('type');

        return $query->when(
            $mogouType,
            function (Builder $query) use ($mogouType): Builder {
                if (is_string($mogouType) && strpos($mogouType, ',') !== false) {
                    $mogouType = explode(',', $mogouType);

                    foreach ($mogouType as $key => $value) {
                        $mogouType[$key] = MogouTypeEnum::getMogouType($value);
                    }

                    return $query->whereIn('mogou_type', $mogouType);
                } else {
                    return $query->where('mogou_type', MogouTypeEnum::getMogouType($mogouType));
                }
            }
        );
    }

    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */
    public function scopeSearch(Builder $query): Builder
    {
        $search = request()->input('search');
        return $query->when(
            $search,
            function (Builder $query) use ($search): Builder {
                return $query->whereRaw('LOWER(title) LIKE ?', [strtolower($search) . '%']);
            }
        );
    }

    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */
    public function scopeFilterCategory(Builder $query, bool $orWhere = true): Builder
    {
        $category = request()->input('category');

        return $query->when(
            $category,
            function (Builder $query) use ($orWhere, $category): Builder {
                return $orWhere ? $query->orWhereHas(
                    'categories',
                    function (Builder $query) use ($category): Builder {
                        return $query->where('categories.id', $category);
                    }
                ) : $query->whereHas(
                    'categories',
                    function (Builder $query) use ($category): Builder {
                        return $query->where('categories.id', $category);
                    }
                );
            }
        );
    }

    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */
    public function scopeFilterGenres(Builder $query): Builder
    {
        $genres = request()->input('genres');

        // Convert to an array and remove empty values
        $genres = array_filter(explode(',', $genres));

        return $query->when(!empty($genres), function (Builder $query) use ($genres): Builder {
            foreach ($genres as $genre) {
                $query->whereHas('categories', function (Builder $query) use ($genre): Builder {
                    return $query->where('categories.title', $genre);
                });
            }
            return $query;
        });
    }



    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */
    public function scopeYear(Builder $query): Builder
    {
        $year = request()->input('year');
        return $query->when(
            $year,
            function (Builder $query) use ($year): Builder {
                return $query->where('released_year', $year);
            }
        );
    }

    /**
     * Scope a query to order results by rating.
     *
     * @param  Builder<Mogou>  $query
     * @return Builder<Mogou>
     */
    public function scopePublishedOnly(Builder $query, bool $strict = false): Builder
    {
        if ($strict) {
            return $query->where('status', 1);
        }
        return $query;
    }

    /**
     * scopeByTotalChapters
     *
     * @param  Builder<Mogou> $query
     * @return Builder<Mogou>
     */
    public function scopeByTotalChapters(Builder $query): Builder
    {
        $chapters_count_order = request('chapters_count_order');

        return $query->when($chapters_count_order, function (Builder $builder) use ($chapters_count_order): Builder {
            // return $builder->order_by("total_chapters",$chapters_count_order);
            return $builder->orderBy("total_chapters", $chapters_count_order);
        });
    }
    
    /**
     * scopeBySorting
     *
     * @param  Builder<Mogou> $query
     * @return Builder<Mogou>
     */
    public function scopeBySorting(Builder $query): Builder
    {
        $orderBy = request('order_by');
        $orderByDirection = request('order_by_direction', 'desc');

        if ($orderBy == 'popular') {
            $popularIds = (new MogouService())->getMogouByPopularity();
            return $query->whereIn('id', $popularIds);
        }

        $sortColumn = match ($orderBy) {
            'rating' => 'rating',
            'latest' => 'created_at',
            default => 'created_at',
        };

        return $query->orderBy($sortColumn, $orderByDirection);
    }
}
