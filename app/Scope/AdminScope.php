<?php

namespace App\Scope;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;

trait AdminScope
{
    /**
     * scopeSearchAdmin
     *
     * @param  Builder<Admin>  $query
     * @return Builder<Admin>
     */
    public function scopeSearchAdmin(Builder $query): Builder
    {
        return $query->when(
            request('search'), function (Builder $query): Builder {
                return $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            }
        );
    }
}
