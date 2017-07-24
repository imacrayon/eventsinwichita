<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * The Eloquent builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */

    protected $builder;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Create a new ThreadFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters.
     *
     * @param  Builder $builder
     * @return Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->all() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    public function all()
    {
        return $this->request->intersect($this->filters);
    }

    /**
     * Fetch relevant filter from the request.
     *
     * @return array
     */
    public function get($key, $default = null)
    {
        return array_get($this->all(), 'start_time', $default);
    }

    /**
     * Add relevant filters from the request.
     *
     * @return array
     */
    public function add($filters)
    {
        $this->request->merge($filters);

        return $this;
    }
}
