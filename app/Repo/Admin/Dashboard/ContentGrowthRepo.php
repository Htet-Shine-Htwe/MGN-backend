<?php

namespace App\Repo\Admin\Dashboard;

use App\Models\Admin;
use App\Models\ChapterAnalysis;
use App\Models\Mogou;
use App\Models\SubMogou;
use App\Models\UserFavorite;
use App\Repo\Admin\SubMogouRepo\MogouPartitionFind;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ContentGrowthRepo
{

    public function __construct(protected string $startDate, protected string $endDate) {}

    public function mostChapterUploadedAdmins() : array
    {
        $subMogou = new SubMogou();
        $tables = $subMogou->getCreatedPartitions();

        $data = collect();

        foreach ($tables as $table) {
            $query = DB::table($table)
                ->whereBetween("$table.created_at", [$this->startDate, $this->endDate])
                ->join('admins', 'admins.id', '=', "$table.creator_id") // Use table alias correctly
                ->select('admins.name', DB::raw('COUNT(*) as total'))
                ->groupBy('admins.name')
                ->get();

            $data = $data->merge($query); // Merge results while keeping it a collection
        }

        return $data->groupBy('name')->map(function ($group) {
            return [
                'name' => $group->first()->name,
                'chapters' => $group->sum('total') // Sum all total counts for the same name
            ];
        })->values()->toArray();

    }

    public function chapterUploadedBetweenTimePeriod() :array
    {
        $subMogou = new SubMogou();
        $tables = $subMogou->getCreatedPartitions();

        $data = collect();

        foreach ($tables as $table) {
            $query = DB::table($table)
                ->whereBetween("$table.created_at", [$this->startDate, $this->endDate])
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw("EXTRACT(week FROM created_at) - EXTRACT(week FROM DATE_TRUNC('month', created_at)) + 1 as week_number")
                )
                ->groupBy('week_number')
                ->orderBy('week_number')
                ->get();

            $data = $data->merge($query);
        }

        // Adjust weeks to only have 4 weeks in February 2025
        $finalData = $data->groupBy('week_number')->map(function ($group, $week) {
            return [
                'week' => 'Week ' . $week,
                'chapters' => $group->sum('total')
            ];
        })->values();

        return $finalData->take(4)->toArray();
    }

    public function getContentByFavorites() : array
    {
        $favorite_id = UserFavorite::query()
            ->join('mogous', 'mogous.id', '=', 'user_favorites.mogou_id') // Join mogou table
            ->whereBetween('user_favorites.created_at', [$this->startDate, $this->endDate])
            ->select('mogous.title as name', DB::raw('COUNT(*) as count'))
            ->groupBy('name') // Group by mogou title (or mogou_id depending on your structure)
            ->orderByDesc('count')
            ->limit(5)
            ->get()->toArray();

        return $favorite_id;
    }

    public function getMostViewedContents() : array
    {
        $thisWeekViews = ChapterAnalysis::query()
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->select('sub_mogou_id','mogou_id', DB::raw('SUM(sub_mogou_id) as total_views'))
            ->groupBy('sub_mogou_id','mogou_id')
            ->orderByDesc('total_views')
            ->limit(10)->get()->toArray();

        foreach ($thisWeekViews as $key => $data) {
            $subMogou =  MogouPartitionFind::getSubMogou("id", $data['mogou_id'])->where('id', $data['sub_mogou_id'])->firstOrFail();

            $thisWeekViews[$key]['sub_mogou_title'] = $subMogou->title;
            $thisWeekViews[$key]['mogou_title'] = $subMogou->mogou->title;
            $thisWeekViews[$key]['cover'] = $subMogou->mogou->cover;
            $thisWeekViews[$key]['created_at'] = $subMogou->created_at;
        }


        return $thisWeekViews;
    }

}
