<?php

namespace App;

class Source extends Model
{
    const NAME_EVENTBRITE = 'eventbrite';

    const NAME_TICKETMASTER = 'ticketmaster';

    const NAME_WICHITA360 = 'wichita360';

    const NAME_KIRBYS = 'kirbys';

    protected $casts = [
        'details' => 'json',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class)->withTrashed()->withDenied();
    }

    public function getUrlAttribute()
    {
        $urls = [
            self::NAME_EVENTBRITE => "https://www.eventbrite.com/e/{$this->key}",
            self::NAME_TICKETMASTER => "http://www.ticketmaster.com/event/{$this->key}",
            self::NAME_WICHITA360 => "https://www.360wichita.com/event/{$this->key}",
            self::NAME_KIRBYS => 'http://kirbysbeerstore.com',
        ];

        return $urls[$this->name] ?? null;
    }
}
