<?php

namespace App\Models;

use Database\Factories\ChildSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildSection extends Model
{
    /** @use HasFactory<ChildSectionFactory> */
    use HasFactory;

    protected $guarded=[];

    public $timestamps = false;

}
