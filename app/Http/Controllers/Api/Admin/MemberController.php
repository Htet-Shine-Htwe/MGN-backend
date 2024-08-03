<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repo\Admin\AdminMemberRepo;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(private AdminMemberRepo $adminMemberRepo)
    {

    }

    public function create()
    {

    }
}
