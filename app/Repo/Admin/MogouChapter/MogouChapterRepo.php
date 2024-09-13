<?php

namespace App\Repo\Admin\MogouChapter;

use App\Models\SubMogou;

class MogouChapterRepo
{
    public function create($data)
    {
        return SubMogou::create($data);
    }

    public function update($data, SubMogou $mogouChapter)
    {
        $mogouChapter->update($data);
        return $mogouChapter;
    }

    public function delete(SubMogou $mogouChapter)
    {
        $mogouChapter->delete();
    }
}
