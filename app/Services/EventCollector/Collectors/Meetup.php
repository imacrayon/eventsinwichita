<?php

namespace App\Services\EventCollector\Collectors;

use App\Place;
use Carbon\Carbon;
use App\Services\EventCollector\Contracts\Collector;

class Meetup extends AbstractCollector implements Collector
{
    /**
     * The base Meetup URL.
     *
     * @var string
     */
    protected $baseUrl = 'https://api.meetup.com';

    /**
     * The Graph API version for the request.
     *
     * @var string
     */
    protected $version = '2';

    protected function getEventUrl($id)
    {
        return "$this->baseUrl/{$this->version}/events?offset=0&format=json&limited_events=False&photo-host=public&page=20&fields=&order=time&desc=false&status=upcoming&venue_id={$id}";
    }

    /**
     * Cast a date value from Facebook's event resource to a DateTime.
     * @param int|string $value
     * @return \DateTime
     */
     protected function castToDateTime($value)
     {
         return Carbon::createFromTimestamp($value / 1000);
     }


    /**
     * Get the location's events.
     * @param string $id The location id.
     * @return string The URL
     */
    protected function getEvents(Place $place)
    {
        $response = $this->getHttpClient()->get(
            $this->getEventUrl($place->meetup_id),
            [
                'headers' => ['Accept' => 'application/json'],
                'http_errors' => true
            ]
        );

        return json_decode($response->getBody())->results;
    }

    /**
     * Get all events associated with a Facebook location and then
     * store or update each.
     * @param  [type] $location [description]
     * @return [type] [description]
     */
    public function collectEvents(Place $place)
    {
        $found = [];
        foreach($this->getEvents($place) as $event)
        {
            $start = $event->time + $event->utc_offset;
            $found[] = $this->storeOrUpdateEvent([
                'name'        => $event->name,
                'start_time'  => $this->castToDateTime($start),
                'end_time'    => isset($event->duration) ? $this->castToDateTime($start + $event->duration) : $this->castToDateTime($start)->endOfDay(),
                'place'       => $place,
                'description' => $this->truncate($event->description),
                'meetup_id'   => $event->id,
                'profile'      => ['meetup_url' => $event->event_url]
            ]);
        }

        return $found;
    }
}
