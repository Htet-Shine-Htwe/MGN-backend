<?php

namespace App\Repo\Admin\Report;

use App\Http\Requests\ReportActionRequest;
use App\Models\Report;
use HydraStorage\HydraStorage\Service\Option\MediaOption;
use HydraStorage\HydraStorage\Traits\HydraMedia;

class CreateReportRepo
{
    use HydraMedia;

    public function create(ReportActionRequest $request): Report
    {
        $report = new Report();
        $report->title = $request->title;
        $report->description = $request->description;
        $report->current_url = $request->current_url;
        if ($request->hasFile('image')) {
            $mediaOption = MediaOption::create()->setQuality(70);
            $report->image = $this->storeMedia($request->image, 'reports', true, $mediaOption);
        }
        $report->user_id = auth()->id() !== null ? (int) auth()->id() : null;
        $report->save();
        return $report;
    }
}
