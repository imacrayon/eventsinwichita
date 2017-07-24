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
    protected $version = 'v2.8';

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

    protected function store(array $data) {
        $event = $this->event->updateOrCreate(['facebook_id' => $data['facebook_id']], $data);
        $event->tags()->sync($data['tags'], false);
    }

    /**
     * Get all events associated with a Facebook location and then
     * store or update each.
     * @param  [type] $location [description]
     * @return [type] [description]
     */
    public function collectEvents(Place $place)
    {
        foreach($this->getEvents($place) as $event)
        {
            // Ignore the event if there is no place set
            if (isset($event->place)) {
                if (!isset($event->place->id) || $event->place->id != $place->facebook_id) {
                    $place = $this->storePlace([
                        'name'        => $place->name,
                        'facebook_id' => $place->id,
                    ]);
                }
                $this->storeOrUpdateEvent(['facebook_id' => $event->id], [
                    'name'        => $event->name,
                    'facebook_id' => $event->id,
                    'start_time'  => $this->castToDateTime($event->start_time),
                    'end_time'    => isset($event->end_time) ? $this->castToDateTime($event->end_time) : null,
                    'place'       => $place,
                    'description' => isset($event->description) ? $event->description : null,
                    'tags'        => isset($event->category)
                                          ? strtolower(trim(str_replace(['_', 'EVENT'], [' ', ''], $event->category)))
                                          : $place->tags->pluck('id')->all()
                ]);
            }
        }
    }
}
