<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $start = $faker->dateTimeBetween('yesterday', '+1 year');
    $end = (clone $start)->add(new DateInterval('PT'.$faker->numberBetween(1, 4).'H'));

    return [
        'name' => $faker->sentence,
        'start' => $start,
        'end' => $end,
        'timezone' => 'UTC',
        'description' => $faker->paragraph,
        'location' => $faker->address,
        'approved_at' => now(),
        'user_id' => factory('App\User'),
    ];
});
