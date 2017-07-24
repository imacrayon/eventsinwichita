<?php

namespace App\Repositories;

use App\User;
use App\Activity;
use Carbon\Carbon;
use App\Filters\ActivityFilters;

class ActivityRepository extends Repository
{
    public function __construct(Activity $activity)
    {
        $this->model = $activity;
    }

    /**
     * Get all.
     *
     * @return Collection
     */
    public function all(ActivityFilters $filters)
    {
        return $this->model->filter($filters)->get();
    }
}
