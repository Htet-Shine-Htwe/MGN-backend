<?php

namespace App\Models;

use App\Enum\ReportStatusEnum;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Report extends Model
{
    use HasFactory,HydraMedia;

    protected $fillable = [
        'title',
        'description',
        'current_url',
        'status',
        'image',
        'user_id'
    ];

    public function getStatusAttribute(int $value): string
    {
        return ReportStatusEnum::getByValue($value);
    }

    public function getCreatedAtAttribute(string $value): string
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    protected function getImageAttribute(?string $value): string
    {
        return $this->getMedia($value ?? "", 'public/reports');
    }


    /**
     * scopeSearch
     *
     * @param  Builder<static> $query
     * @param  ?string $search
     * @return Builder<static>
     */
    public function scopeSearch(Builder $query, ?string $search):  Builder
    {
        return $query->where('title', 'like', $search . '%')
            ->orWhere('description', 'like', $search . '%');
    }

    /**
     * scopeSearch
     *
     * @param  Builder<static> $query
     * @param  ?string $status
     * @return Builder<static>
     */
    public function scopeStatus(Builder $query,?string $status): Builder
    {
        return $query->when($status, function (Builder $query) use ($status){
            $status_value = ReportStatusEnum::getByLabel($status);
            $query->where('status', $status_value);
        });
    }

    /**
     * scopeSortBy
     *
     * @param  Builder<static> $query
     * @param  ?string $sortBy
     * @return Builder<static>
     */
    public function scopeSortBy(Builder $query, ?string $sortBy): Builder
    {
        return $query->orderBy("id", $sortBy);
    }



}
