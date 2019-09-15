<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

trait Approval
{
    protected static function bootApproval()
    {
        static::addGlobalScope('approved', function (Builder $builder) {
            $builder->whereNotNull('approved_at');
        });
    }

    public function scopeWithDenied($query)
    {
        $query->withoutGlobalScope('approved');
    }

    public function approve()
    {
        return $this->forceFill(['approved_at' => $this->freshTimestamp()]);
    }
}
