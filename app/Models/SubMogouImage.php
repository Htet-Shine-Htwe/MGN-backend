<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMogouImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_mogou_id',
        'image',
        'page_number'
    ];

    public function subMogou()
    {
        return $this->belongsTo(SubMogou::class);
    }
}
