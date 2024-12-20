<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'logo',
        'user_side_is_maintenance_mode'
    ];


}
