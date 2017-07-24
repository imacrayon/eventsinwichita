<?php

namespace App\Services\EventCollector;

class Geocoder implements Contracts\Geocoder
{
    protected $base_uri = 'https://maps.google.com/maps/api/geocode/json';

    protected $client;

    private $key;

    public function __construct($key)
    {
        $this->client = new \GuzzleHttp\Client;
        $this->key = $key;
        $this->place = new \App\Place;
    }

    public function getData($address)
    {
        $response = json_decode(
            $this->client->request('GET', $this->base_uri,
                [
                    'http_errors' => true,
                    'query' => [
                        'key'     => $this->key,
                        'address' => $address,
                        // Limit results to the Wichita area
                        'bounds'  => '37.534933,-97.560549|37.856121,-97.100140',
                    ]
                ]
            )->getBody()
        );

        if ($response->status === 'OK') {
            $location = $response->results[0]->geometry->location;
            $components = [];
            foreach($response->results[0]->address_components as $component) {
                foreach($component->types as $type) {
                    // Grab the short name for states
                    $components[$type] = ($type !== 'administrative_area_level_1') ? $component->long_name : $component->short_name;
                }
            }

            $street = '';

            if (array_key_exists('street_number', $components)) {
                $street .= $components['street_number'];
            }

            if (array_key_exists('route', $components)) {
                $street .= ' ' . $components['route'];
            }

            return [
                'name'      => $address,
                'street'    => $street,
                'city'      => array_key_exists('locality', $components) ? $components['locality'] : 'Wichita',
                'state'     => array_key_exists('administrative_area_level_1', $components) ? $components['administrative_area_level_1'] : 'KS',
                'zip'       => array_key_exists('postal_code', $components) ? $components['postal_code'] : '',
                'longitude' => $location->lng,
                'latitude'  => $location->lat,
                'profile'   => []
            ];
        }

        return [
            'name'  => $address,
            'city'  => 'Wichita',
            'state' => 'KS',
        ];
    }
}
