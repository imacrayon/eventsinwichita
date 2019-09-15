<?php

namespace App;

use LinkFinder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use Filterable, SoftDeletes, Approval;

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected function filters()
    {
        return [
            new Filters\Before,
            new Filters\After,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getHtmlAttribute()
    {
        return nl2br((new LinkFinder)->process($this->description));
    }

    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    public function addToCalendarDay($calendar, $start, $end)
    {
        $start = $start ?? $this->start->endOfDay();

        if ($this->start->between($start, $end)) {
            $calendar->put($start, $calendar->get($start, collect())->push($this));
        }
    }
}
