<?php

namespace App\Services\SectionManagement;

use App\Models\BaseSection;
use App\Models\ChildSection;

class SectionManagementService
{
    public function getBySection(string $type): BaseSection
    {
        return BaseSection::where('section_name', $type)->firstOrFail();
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
}
