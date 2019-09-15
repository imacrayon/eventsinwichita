<?php

namespace App;

use Illuminate\Http\Request;

trait Filterable
{
    public function scopeFilter($query, Request $request)
    {
        return tap($query, function ($query) use ($request) {
            $this->requestedFilters($request)->each(function ($filter) use ($request, $query) {
                $filter->apply($request, $query, $request->{$filter->name()});
            });
        });
    }

    public function requestedFilters($request)
    {
        return collect($this->filters())->filter(function ($filter) use ($request) {
            return $request->has($filter->name());
        });
    }
}
