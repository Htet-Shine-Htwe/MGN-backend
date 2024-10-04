<?php

namespace App\Repo\Admin\Mogou;

use App\Enum\MogousStatus;
use App\Http\Requests\MogouActionRequest;
use App\Models\Mogou;
use HydraStorage\HydraStorage\Service\Option\MediaOption;
use HydraStorage\HydraStorage\Traits\HydraMedia;

class MogouActionRepo
{

    use HydraMedia;

    public function create(MogouActionRequest $request): Mogou
    {
        $data = $request->validated();
        $request->validate(
            [
            'title' => 'unique:mogous,title',
            'cover' => 'required|image'
            ]
        );
        $mediaOption = MediaOption::create()->setQuality(70)->get();

        $data['cover'] = $this->storeMedia($request->file('cover'), 'mogou/cover', false, $mediaOption);
        $data['status'] = MogousStatus::ARCHIVED;

        $mogou = Mogou::create($data);

        $categories = $request->input('categories', []);

        $mogou->categories()->sync($categories);

        return $mogou;
    }

    public function update(MogouActionRequest $request, Mogou $mogou): Mogou
    {
        $data = $request->validated();
        $request->validate(
            [
            'title' => 'unique:mogous,title,' . $mogou->slug . ',slug',
            'cover' => 'nullable|image'
            ]
        );

        if ($request->hasFile('cover')) {
            $this->removeMedia("public/mogou/cover/{$mogou->cover}");

            $mediaOption =  MediaOption::create()->setQuality(70)->get();
            $data['cover'] = $this->storeMedia($request->file('cover'), 'mogou/cover', true, $mediaOption);
        }

        $mogou->update($data);

        $categories = $request->input('categories', []);

        $mogou->categories()->sync($categories);

        return $mogou;
    }


    public function delete(Mogou $mogou): void
    {

        $cover_prefix = config('control.mogou.cover.path');

        $full_path = "public/". $cover_prefix  . '/' . $mogou->cover;

        $this->removeMedia($full_path);

        $mogou->delete();
    }

    public function updateStatus(Mogou $mogou,string $status): Mogou
    {
        $mogou->update(['status' => $status]);

        return $mogou;
    }

    public function addNewCategory(Mogou $mogou,string $category_id): void
    {
        $mogou->categories()->attach($category_id);
    }

    public function removeCategory(Mogou $mogou,string $category_id): void
    {
        $mogou->categories()->detach($category_id);
    }

}
