<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index()
    {
        return response()->json(
            [
            'message' => 'Admin report index'
            ]
        );
    }

    public function show($id)
    {
        return response()->json(
            [
            'message' => 'Admin report show'
            ]
        );
    }

    public function updateStatus(Request $request, $id)
    {
        return response()->json(
            [
            'message' => 'Admin report update status'
            ]
        );
    }

    public function delete($id)
    {
        return response()->json(
            [
            'message' => 'Admin report delete'
            ]
        );
    }
}
