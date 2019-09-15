<?php

namespace App\Filters;

use Illuminate\Http\Request;

class Before extends Filter
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
        $query->where(function ($query) use ($value) {
            $query->where('start', '<', $value);
        });
    }
}
