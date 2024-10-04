<?php

namespace App\Repo\Admin\MogouChapter;

use App\Models\SubMogou;

class MogouChapterRepo
{
    public function create(array $data): SubMogou
    {
        return SubMogou::create($data);
    }

    public function update(array $data, SubMogou $mogouChapter): SubMogou
    {
        $mogouChapter->update($data);
        return $mogouChapter;
    }

    public function delete(SubMogou $mogouChapter): void
    {
        $mogouChapter->delete();
    }
}
