<?php

namespace App\Services\SugMogou;

class MogouImage
{

    public function __construct(protected $mogou)
    {
    }

    public function getImages()
    {
        return $this->mogou->mogou_images;
    }

    public function addImage($image)
    {
        $this->mogou->mogou_images()->create([
            'image' => $image
        ]);
    }

    public function removeImage($image)
    {
        $this->mogou->mogou_images()->where('image', $image)->delete();
    }

}
