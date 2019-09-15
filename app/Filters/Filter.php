<?php

namespace App\Filters;

use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

abstract class Filter implements JsonSerializable
{
    /**
     * The displayable label for the filter.
     *
     * @var string
     */
    public $label;

    /**
     * The attribute name for the filter.
     *
     * @var string
     */
    public $name;

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function apply(Request $request, $query, $value);

    public function label()
    {
        return $this->label ?: title_case(Str::snake(class_basename($this), ' '));
    }

    public function name()
    {
        return $this->name ?: Str::snake(class_basename($this));
    }

    /**
     * Prepare the filter for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'label' => $this->label(),
            'name' => $this->name(),
            'value' => '',
        ];
    }
}
