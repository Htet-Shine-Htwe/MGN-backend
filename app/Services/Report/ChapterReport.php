<?php

namespace App\Services\Report;

use App\Models\Mogou;
use App\Models\SubMogou;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChapterReport
{

    /** @var HasMany<SubMogou> */
    protected HasMany $submogouCollection;

    public function __construct(protected Mogou $mogou)
    {
        $this->mogou = $mogou;
        $this->submogouCollection = $mogou->subMogous($mogou->rotation_key);
    }

    public function getTotalViews(): int
    {
        return (int) $this->submogouCollection->sum('views');
    }

    public function getTotalChapters(): int
    {
        return $this->submogouCollection->count();
    }


}
