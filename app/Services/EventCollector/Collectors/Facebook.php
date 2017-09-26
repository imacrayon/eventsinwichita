<?php

namespace App\Services\EventCollector\Collectors;

use App\Place;
use App\Services\EventCollector\Contracts\Collector;

class Facebook extends AbstractCollector implements Collector
{
    /**
     * The event fields being requested.
     *
     * @var array
     */
    protected $fields = [
        'name',
        'description',
        'start_time',
        'end_time',
        'updated_time',
        'place',
        'category'
    ];

    /**
     * The base Facebook Graph URL.
     *
     * @var string
     */
    protected $graphUrl = 'https://graph.facebook.com';

    /**
     * The Graph API version for the request.
     *
     * @var string
     */
    protected $version = 'v2.10';

    protected function getEventUrl($id)
    {
        return $this->graphUrl . '/' . $this->version. '/' . $id . '/events';
    }

    /**
     * Cast a date value from Facebook's event resource to a DateTime.
     * @param int|string $value
     * @return \DateTime
     */
    protected function castToDateTime($value)
    {
        if (is_int($value)) {
            $dt = new \DateTime();
            $dt->setTimestamp($value)->setTimezone($tz);
        } else {
            $tz = new \DateTimeZone('America/Chicago');
            $dt = new \DateTime($value);
            $dt->setTimezone($tz);
        }

        return $dt;
    }

    /**
     * Get the location's events.
     * @param string $id The location id.
     * @return string The URL
     */
    protected function getEvents(Place $place)
    {
        $response = $this->getHttpClient()->get(
            $this->getEventUrl($place->facebook_id) . '?since=' . time() . '&fields=' . implode(',', $this->fields) . '&access_token=' . $this->token,
            [
                'headers' => ['Accept' => 'application/json'],
                'http_errors' => true
            ]
        );

        return json_decode($response->getBody())->data;
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
        foreach($this->getEvents($place) as $event) {
            // Ignore the event if there is no place set
            if (!isset($event->place)) return;

            if (!isset($event->place->id)) {
                $place = $this->storePlace([
                    'name' => $event->place->name
                ]);
            } else if ($event->place->id != $place->facebook_id) {
                // Some Facebook places are associated with multiple page IDs.
                // We track associated page IDs in the profile of each Place.
                // If the event is happening at another place check that it is
                // not a known alias before storing it in the database.
                $aliases = isset($place->profile['facebook_aliases'])
                    ? $place->profile['facebook_aliases']
                    : [];
                if (!in_array($event->place->id, $aliases)) {
                    $place = $this->storePlace([
                        'name' => $event->place->name,
                        'facebook_id' => $event->place->id
                    ]);
                }
            }

            $found[] = $this->storeOrUpdateEvent([
                'name'        => $event->name,
                'facebook_id' => $event->id,
                'start_time'  => $this->castToDateTime($event->start_time),
                'end_time'    => isset($event->end_time) ? $this->castToDateTime($event->end_time) : null,
                'place'       => $place,
                'description' => isset($event->description) ? $this->truncate($event->description) : null,
                'tags'        => isset($event->category)
                                        ? strtolower(trim(str_replace(['_', 'EVENT'], [' ', ''], $event->category)))
                                        : $place->tags->pluck('id')->all()
            ]);
        }

        return $found;
    }
}
