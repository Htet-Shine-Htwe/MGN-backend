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
        return Report::orderBy("id",'desc')->paginate(10);
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
