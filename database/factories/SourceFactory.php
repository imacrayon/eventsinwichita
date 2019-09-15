<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Event;
use App\Source;
use Faker\Generator as Faker;

$factory->define(Source::class, function (Faker $faker) {
    return [
        'name' => 'eventbrite',
        'key' => $faker->uuid,
        'event_id' => factory(Event::class),
    ];
});
