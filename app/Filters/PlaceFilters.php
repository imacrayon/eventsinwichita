<?php

namespace App\Filters;

class PlaceFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['tags', 'user_id', 'name'];

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return Builder
     */
    protected function tags($tagIds)
    {
        if (!is_array($tagIds)) {
            $tagIds = explode(',', $tagIds);
        }

        $this->builder->whereHas('tags', function($tags) use ($tagIds) {
            $tags->whereIn('id', $tagIds);
        }, '=', count($tagIds));
    }

    protected function user_id($userId)
    {
        $this->builder->where('user_id', $userId);
    }

    protected function name($name)
    {
        $this->builder->where('name', 'like', "%{$name}%");
    }
}
