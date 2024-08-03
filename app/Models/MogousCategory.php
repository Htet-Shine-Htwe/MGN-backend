<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MogousCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'mogou_id',
        'category_id',
    ];

    public function mogou()
    {
        return $this->belongsTo(Mogou::class);
    }
}
