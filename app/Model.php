<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    protected $guarded = [];

    protected $perPage = 25;

    public function resolveRouteBinding($value)
    {
        return $this->where($this->getRouteKeyName(), $value)
            ->when(in_array(SoftDeletes::class, class_uses($this)), function ($query) {
                $query->withTrashed();
            })
            ->when(in_array(Approval::class, class_uses($this)), function ($query) {
                $query->withDenied();
            })->first();
    }
}
