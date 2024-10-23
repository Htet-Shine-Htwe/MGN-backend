<?php
namespace App\Scope;

use App\Enum\MogouFinishStatus;
use App\Enum\MogouTypeEnum;
use App\Models\Mogou;
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
            request('order_by_rating'), function (Builder $query): Builder {
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
            $status, function (Builder $query) use ($orWhere, $status): Builder {
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
            request('legal_only'), function (Builder $query): Builder {
                return $query->where('legal_age', request('legal_only'));
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
            $finishStatus, function (Builder $query) use ($finishStatus): Builder {
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
        $mogouType = request()->input('mogou_type');

        return $query->when(
            $mogouType, function (Builder $query) use ($mogouType): Builder {
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
            $search, function (Builder $query) use ($search): Builder {
                return $query->orWhere('title', 'like', $search.'%');

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
            $category, function (Builder $query) use ($orWhere, $category): Builder {
                return $orWhere ? $query->orWhereHas(
                    'categories', function (Builder $query) use ($category): Builder {
                        return $query->where('categories.id', $category);
                    }
                ) : $query->whereHas(
                    'categories', function (Builder $query) use ($category): Builder {
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
    public function scopeYear(Builder $query): Builder
    {
        $year = request()->input('year');
        return $query->when(
            $year, function (Builder $query) use ($year): Builder {
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
}
