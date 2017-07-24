<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FilterEventsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_can_filter_events_by_tag()
    {
        $tag = create('App\Tag');

        $eventWithTag = create('App\Event');
        $eventWithTag->tags()->save($tag);

        $eventWithoutTag = create('App\Event');

        $this->get('/api/events?tags%5B0%5D=' . $tag->id)
             ->assertJsonFragment(['name' => $eventWithTag->name])
             ->assertJsonMissing(['name' => $eventWithoutTag->name]);
    }

    /** @test */
    function user_can_filter_events_by_start_time()
    {
        $now = Carbon::now();

        $upcomingEvent = create('App\Event', ['end_time' => $now->copy()->addHour()]);

        $pastEvent = create('App\Event', ['end_time' => $now->copy()->subMinute()]);

        $this->get('/api/events/?start_time=' . urlencode($now->toDateTimeString()))
             ->assertJsonFragment(['name' => $upcomingEvent->name])
             ->assertJsonMissing(['name' => $pastEvent->name]);
    }

    /** @test */
    function user_can_filter_events_by_end_time()
    {
        $now = Carbon::now();

        $upcomingEvent = create('App\Event', ['start_time' => $now->copy()->subHour()]);

        $futureEvent = create('App\Event', ['start_time' => $now->copy()->addMonth()]);

        $this->get('/api/events?end_time=' . urlencode($now->toDateTimeString()))
             ->assertJsonFragment(['name' => $upcomingEvent->name])
             ->assertJsonMissing(['name' => $futureEvent->name]);
    }

    /** @test */
    function user_can_filter_events_by_user_id()
    {
        $user = create('App\User');

        $eventbyUser = create('App\Event', ['user_id' => $user->id]);

        $eventNotByUser = create('App\Event');

        $this->get('/api/events?user_id=' . urlencode($user->id))
             ->assertJsonFragment(['name' => $eventbyUser->name])
             ->assertJsonMissing(['name' => $eventNotByUser->name]);
    }

        /** @test */
    function user_can_filter_events_by_limit()
    {
        $events = create('App\Event', [], 5);

        $response = $this->get('/api/events?limit=3');

        $this->assertCount(3, $response->json());
    }
}
