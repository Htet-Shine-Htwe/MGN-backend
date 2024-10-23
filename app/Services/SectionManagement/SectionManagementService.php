<?php

namespace App\Services\SectionManagement;

use App\Enum\MogousStatus;
use App\Models\BaseSection;
use App\Models\ChildSection;
use App\Models\Mogou;

class SectionManagementService
{
    public function getBySection(string $type): BaseSection
    {
        return BaseSection::where('section_name', $type)->firstOrFail();
    }

    public function getMogouSection(string $type)
    {
        $mogous_ids = $this->getBySection($type)->childSections->pluck('is_visible', 'pivot_key')->toArray();

        $mogou = Mogou::select("id", "title", "slug", "cover", "rotation_key", "description", "finish_status", 'mogou_type', 'status', "rating")
            ->where('status', MogousStatus::PUBLISHED->value)
            ->whereIn('id', array_keys($mogous_ids)) // Using array_keys for 'pivot_key' IDs
            ->with('categories:title')
            ->get()
            ->toArray();

        foreach ($mogou as $key => $value) {
            $mogou[$key]['is_selected'] = true;
            $mogou[$key]['is_visible'] = $mogous_ids[$value['id']] ?? 1; // Check 'is_visible' using the fetched 'id'
        }

        return $mogou;

    }

    public function attachNewChild(string $type,string $child): BaseSection
    {
        $baseSection = $this->getBySection($type);
        $max_limit = $baseSection->component_limit;

        if ($baseSection->childSections->count() >= $max_limit) {
            throw new \Exception("You can't add more than $max_limit components to this section", 500);
        }

        ChildSection::create([
            "pivot_key" => $child,
            "base_section_id" => $baseSection->id
        ]);

        return $baseSection;
    }

    public function removeChild(string $type,string $child): BaseSection
    {
        $baseSection = $this->getBySection($type);

        $baseSection->childSections()->where('pivot_key', $child)->delete();

        return $baseSection;
    }

    public function searchMogou(string|null $search,string $type): array
    {
        $mogous = Mogou::
        select('id','title','slug','description','cover','total_chapters','created_at')
        ->where('title', 'like', "$search%")
        ->where("status", MogousStatus::PUBLISHED->value)
        ->take(20)
        ->get();

        $existedMogou = BaseSection::where('section_name', $type)->firstOrFail()->childSections->pluck('pivot_key')->toArray();

        $mogous = $mogous->map(function ($mogou) use ($existedMogou) {
            $mogou->is_selected = in_array($mogou->id, $existedMogou);
            return $mogou;
        });
        return $mogous->toArray();
    }

    public function setToggleVisibility(string $type, string $child, int $visibility): BaseSection
    {
        $baseSection = $this->getBySection($type);

        $baseSection->childSections()->where('pivot_key', $child)->update(['is_visible' => $visibility]);

        return $baseSection;
    }

    public function truncateSection(string $type): BaseSection
    {
        $baseSection = $this->getBySection($type);

        $baseSection->childSections()->delete();

        return $baseSection;
    }
}
