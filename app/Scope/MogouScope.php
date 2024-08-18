<?php

namespace App\Scope;

use App\Enum\MogouTypeEnum;

trait MogouScope
{
    public function scopeLastFourChapters($query)
    {
        return $query->with(['subMogous' => function($q){
            $q->select('id','chapter_number','mogou_id')
                ->orderBy('id', 'desc')
                ->limit(3);
        }]);
    }

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
            return $query->where('finish_status', $finishStatus);
        });
    }

    public function scopeByMogouType($query)
    {
        $mogouType = request()->input('mogou_type');

        return $query->when($mogouType, function($query) use ($mogouType){
            $mogouTypeValue = MogouTypeEnum::getMogouType($mogouType);
            return $query->where('mogou_type', $mogouTypeValue);
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

    public function scopeByMogouTotalViewCount($query)
    {

    }
}
