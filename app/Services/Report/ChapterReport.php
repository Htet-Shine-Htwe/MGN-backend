<?php

namespace App\Services\Report;

use App\Models\Mogou;

class ChapterReport
{

    protected $submogouCollection;

    public function __construct(protected Mogou $mogou)
    {
        $this->mogou = $mogou;
        $this->submogouCollection = $mogou->subMogous($mogou->rotation_key);
    }

    public function getTotalViews()
    {
        return $this->submogouCollection->sum('views');
    }

    public function getTotalChapters()
    {
        return $this->submogouCollection->count();
    }


}
