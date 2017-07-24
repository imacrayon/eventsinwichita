<?php

namespace App\Filters;

use Carbon\Carbon;

class ActivityFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['start_time', 'end_time', 'user_id', 'limit'];

    protected function user_id($userId)
    {
        $this->builder->where('user_id', $userId);
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
