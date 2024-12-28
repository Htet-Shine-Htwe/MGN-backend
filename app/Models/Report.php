<?php

namespace App\Models;

use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected function getImageAttribute(string $value): string
    {
        return $this->getMedia($value, 'public/reports');
    }

}
