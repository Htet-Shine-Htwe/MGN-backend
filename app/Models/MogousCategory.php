<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MogousCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'mogou_id',
        'category_id',
    ];

    /**
     * mogou
     *
     * @return BelongsTo<Mogou, MogousCategory>
     */
    public function mogou(): BelongsTo
    {
        return $this->belongsTo(Mogou::class);
    }
}
