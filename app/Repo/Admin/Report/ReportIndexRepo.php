<?php

namespace App\Repo\Admin\Report;

use App\Models\Report;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ReportIndexRepo
{

    /**
     * index
     *
     * @param  Request $request
     * @return LengthAwarePaginator<Report>
     */
    public function index(Request $request) : LengthAwarePaginator
    {
        return Report::search($request->search)
        ->status($request->status)
        ->sortBy($request->sort_by ?? 'asc')
        ->paginate(6);
    }

    public function show(string $id) : Report
    {
        return Report::where("id",$id)->firstOrFail();
    }

    public function updateStatus(Request $request, string $id) : void
    {
        $report = Report::where("id",$id)->firstOrFail();
        $report->status = $request->status;
        $report->save();
    }
}
