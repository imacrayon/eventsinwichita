<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'avatar'         => $faker->imageUrl(80, 80),
        'role'           => 3,
        'remember_token' => str_random(10),
        'settings'       => [
            'email_notifications' => true
        ]
    ];
});

$factory->define(\App\Tag::class, function (Faker\Generator $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => $name,
    ];
});

$factory->define(\App\Place::class, function (Faker\Generator $faker) {
    return [
        'name'        => $faker->sentence,
        'slug'        => $faker->slug,
        'street'      => $faker->streetName,
        'city'        => $faker->city,
        'state'       => $faker->stateAbbr,
        'zip'         => $faker->postcode,
        'latitude'    => $faker->latitude,
        'longitude'   => $faker->longitude,
        'user_id'     => function () {
                             return factory(\App\User::class)->create()->id;
                         },
        'facebook_id' => $faker->ean8,
        'meetup_id'   => $faker->ean8,
        'profile'     => [
            'twitter'     => $faker->word,
            'website'     => $faker->url,
            'email'       => $faker->safeEmail,
            'phone'       => $faker->phoneNumber,
        ]
    ];
});

$factory->define(\App\Event::class, function (Faker\Generator $faker) {
    $start = Carbon\Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+6 days')->getTimestamp());
    $end = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start)->addHour();

    return [
        'name'        => $faker->sentence,
        'start_time'  => $start,
        'end_time'    => $end,
        'place_id'    => function () {
                             return factory(\App\Place::class)->create()->id;
                         },
        'description' => $faker->paragraphs(1, true),
        'user_id'     => function () {
                             return factory(\App\User::class)->create()->id;
                         },
        'facebook_id' => $faker->ean8,
        'meetup_id'   => $faker->ean8
    ];
});

$factory->define(\App\Comment::class, function (Faker\Generator  $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'subject_id' => function () {
            return factory('App\Event')->create()->id;
        },
        'subject_type' => 'App\Event',
        'body' => $faker->paragraphs(1, true)
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function ($faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => 'App\Notifications\NewComment',
        'notifiable_id' => function () {
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']
    ];
});
