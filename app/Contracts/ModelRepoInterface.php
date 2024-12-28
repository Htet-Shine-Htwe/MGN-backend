<?php

namespace App\Contracts;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ModelRepoInterface
{
    public function get(Request $request) : mixed;

    public function collection(): mixed;

}
