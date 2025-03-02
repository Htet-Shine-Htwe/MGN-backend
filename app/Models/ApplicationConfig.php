<?php

namespace App\Models;

use Database\Factories\ApplicationConfigFactory;
use HydraStorage\HydraStorage\Traits\HydraMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationConfig extends Model
{
    /** @use HasFactory<ApplicationConfigFactory> */
    use HasFactory, HydraMedia;
    protected $fillable = [
        'title',
        'logo',
        'user_side_is_maintenance_mode',
        'water_mark',
        'intro_a',
        'outro_a',
        'intro_b',
        'outro_b',
    ];

    protected $casts = [
        'user_side_is_maintenance_mode' => 'boolean',
    ];

    public function getLogoAttribute(string $value): string
    {

        return $this->getMedia($value,'public/config');
    }

    public function getWaterMarkAttribute(?string $value): string
    {
        if($value){
            return $this->getMedia($value,'public/config');
        }
        return '';
    }

    public function getIntroAAttribute(?string $value): string
    {
        if($value){
            return $this->getMedia($value,'public/config');
        }
        return '';
    }
    public function getOutroAAttribute(?string $value): string
    {
        if($value){
            return $this->getMedia($value,'public/config');
        }
        return '';
    }

    public function getIntroBAttribute(?string $value): string
    {
        if($value){
            return $this->getMedia($value,'public/config');
        }
        return '';
    }

    public function getOutroBAttribute(?string $value): string
    {
        if($value){
            return $this->getMedia($value,'public/config');
        }
        return '';
    }
}
