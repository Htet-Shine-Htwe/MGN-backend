<?php

namespace App\Services\Mogou;

use App\Models\ChapterAnalysis;

class MogouService
{
    public function getMogouByPopularity() : array
    {
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->subDay();

        // select count of mogou_id withtin this month
        $instance = ChapterAnalysis::
        selectRaw('mogou_id, COUNT(mogou_id) as views')
        ->whereBetween('date', [$startDate, $endDate])
        ->groupBy('mogou_id')
        ->orderByDesc('views')
        ->limit(100)
        ->get();

        return $instance->pluck('mogou_id')->toArray();
    }
}
