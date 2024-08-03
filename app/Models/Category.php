<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug'];

    public $timestamps = false;

    protected $hidden = ['pivot'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($category){
            $category->slug = Str::slug($category->title);
        });

        static::updating(function($category){
            $category->slug = Str::slug($category->title);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', '%'.$search.'%');
    }

    public function mogous()
    {
        return $this->belongsToMany(Mogou::class, 'mogous_categories');
    }

    public function scopeWithMogousCount($query)
    {
        return $query->when(request('with_mogous_count'), function($query){
            return $query->withCount('mogous');
        });
    }

    public function scopeOrderByMogousCount($query)
    {
        return $query->when(request('order_by_mogous_count'), function($query){
            return $query->withCount('mogous')->orderBy('mogous_count', request('order_by_mogous_count'))
            ->orderBy('id', 'desc');
        });
    }

}
