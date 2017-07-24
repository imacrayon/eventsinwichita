<?php

namespace App\Traits;

use App\Activity;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        foreach (static::getModelEvents() as $event) {
            static::$event(function ($subject) use ($event) {
                $subject->recordActivity($event);
            });
        }

        static::deleting(function ($subject) {
            $subject->activities->each->delete();
        });
    }

    protected function recordActivity($event)
    {
        $this->activities()->create([
            'name' => $this->getActivityName($event),
            'user_id' => $this->user_id,
            'user_agent' => Activity::getUserAgent(),
            'ip_address' => Activity::getIpAddress(),
            'country' => Activity::getCountry()
        ]);
    }

    protected function getActivityName($event)
    {
        $subject = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$subject}";
    }

    public function activities()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * Get the model events to record activity for.
     *
     * @return array
     */
    protected static function getModelEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }
        return [
            'created', 'updated',
        ];
    }
}
