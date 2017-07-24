<?php

namespace App\Services\EventCollector\Collectors;

use App\Repositories\EventRepository;
use App\Repositories\PlaceRepository;
use App\Services\EventCollector\Contracts\Collector;

abstract class AbstractCollector implements Collector
{
    protected $token;

    public function __construct($token, EventRepository $events, PlaceRepository $places) {
        $this->token = $token;
        $this->places = $places;
        $this->events = $events;
    }

    /**
     * Get a fresh instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        return new \GuzzleHttp\Client;
    }

    protected function storePlace(array $data)
    {
        // Store will just return a place if it already exists in the database.
        // Otherwise, it will create a new place for user with id `1`.
        return $this->places->store($data, 1);
    }

    protected function storeOrUpdateEvent(array $attributes, array $data)
    {
        $user_id = $data['place']->user_id;
        $data['place_id'] = $data['place']->id;

        return $this->events->storeOrUpdate($attributes, $data, $user_id);

    }
}
