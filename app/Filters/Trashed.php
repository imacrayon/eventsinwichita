<?php

namespace App\Filters;

use Illuminate\Http\Request;

class Trashed extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     */
    public function apply(Request $request, $query, $value)
    {
        if ($value === 'with') {
            $query->withTrashed();
        } elseif ($value === 'only') {
            $query->onlyTrashed();
        }
    }
}
