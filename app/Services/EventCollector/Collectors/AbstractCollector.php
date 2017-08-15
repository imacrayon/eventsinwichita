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
        $place = $this->places->store($data, 1);
        echo "Created place {$place->name} ({$place->id})" . PHP_EOL;
        return $place;
    }

    protected function storeOrUpdateEvent(array $data)
    {
        $user_id = $data['place']->user_id;
        $data['place_id'] = $data['place']->id;
        unset($data['place']);

        $event = $this->events->find($data);
        if ($event) return $this->events->update($data, $event);

        $event = $this->events->store($data, $user_id);
        echo "Created event {$event->name} ({$event->id})" . PHP_EOL;
        return $event;
    }

    protected function truncate($string, $length = 1000, $append = "&hellip;") {
        $string = trim($string);

        $string = strip_tags($string);

        if(strlen($string) > $length) {
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0] . $append;
        }

        return $string;
    }
}
