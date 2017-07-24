<?php

namespace App\Filters;

use Carbon\Carbon;

class EventFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['tags', 'start_time', 'end_time', 'user_id', 'place_id', 'limit'];

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

    protected function place_id($placeId)
    {
        $this->builder->where('place_id', $placeId);
    }

    protected function start_time($date)
    {
        $this->builder->where('end_time', '>=', Carbon::createFromFormat('Y-m-d H:i:s', $date));
    }

    protected function end_time($date)
    {
        $this->builder->where('start_time', '<', Carbon::createFromFormat('Y-m-d H:i:s', $date));
    }

    protected function limit($limit)
    {
        $this->builder->limit((int) $limit);
    }
}
