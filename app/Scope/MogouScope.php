<?php

namespace App\Scope;

use App\Enum\MogouFinishStatus;
use App\Enum\MogouTypeEnum;

trait MogouScope
{


    public function scopeOrderByRating($query)
    {
        return $query->when(request('order_by_rating'), function($query){
            return $query->orderBy('rating', request('order_by_rating'));
        });
    }

    public function scopeFilterStatus($query,bool $orWhere = true)
    {
        $status = request()->input('status');
        return $query->when($status, function($query) use ($orWhere,$status){
            return $orWhere ? $query->orWhere('status', $status) : $query->where('status', $status);
        });
    }

    public function scopeLegalOnly($query)
    {
        return $query->when(request('legal_only'), function($query){
            return $query->where('legal_age', request('legal_only'));
        });
    }

    public function scopeByFinishStatus($query)
    {
        $finishStatus = request()->input('finish_status');

        return $query->when($finishStatus, function($query) use ($finishStatus){
             if (is_string($finishStatus) && strpos($finishStatus, ',') !== false) {
                $finishStatus = explode(',', $finishStatus);

                foreach ($finishStatus as $key => $value) {
                    $finishStatus[$key] = MogouFinishStatus::getFinishStatus($value);
                }

                return $query->whereIn('finish_status', $finishStatus);
            }
            else{
                return $query->where('finish_status', MogouFinishStatus::getFinishStatus($finishStatus));
            }
        });
    }

    public function scopeByMogouType($query)
    {
        $mogouType = request()->input('mogou_type');

        return $query->when($mogouType, function($query) use ($mogouType){
            if (is_string($mogouType) && strpos($mogouType, ',') !== false) {
                $mogouType = explode(',', $mogouType);

                foreach ($mogouType as $key => $value) {
                    $mogouType[$key] = MogouTypeEnum::getMogouType($value);
                }

                return $query->whereIn('mogou_type', $mogouType);
            }
            else{
                return $query->where('mogou_type', MogouTypeEnum::getMogouType($mogouType));
            }
        });
    }

    public function scopeSearch($query)
    {
        $search = request()->input('search');
        return $query->when($search, function($query) use ($search){
            return $query->where('title', 'like', '%'.$search.'%')
                ->orWhere('author', 'like', '%'.$search.'%');
        });
    }

    public function scopeFilterCategory($query,bool $orWhere = true)
    {
        $category = request()->input('category');

        return $query->when($category, function($query) use ($orWhere,$category){
            return $orWhere ? $query->orWhereHas('categories', function($query) use ($category){
                return $query->where('categories.id', $category);
            }) : $query->whereHas('categories', function($query) use ($category){
                return $query->where('categories.id', $category);
            });
        });
    }

    public function scopeYear($query)
    {
        $year = request()->input('year');
        return $query->when($year, function($query) use ($year){
            return $query->where('released_year', $year);
        });
    }

    public function scopePublishedOnly($query,bool $strict = false)
    {
        if($strict){
            return $query->where('status', 1);
        }
        return $query;
    }
}
