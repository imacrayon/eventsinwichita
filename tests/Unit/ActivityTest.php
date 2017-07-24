<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_records_activity_when_an_event_is_created()
    {
        $this->signIn();

        $event = create('App\Event');

        $this->assertDatabaseHas('activities', [
            'name' => 'created_event',
            'user_id' => $event->user_id,
            'subject_id' => $event->id,
            'subject_type' => 'App\Event'
        ]);

        $activity = \App\Activity::first();

        $this->assertEquals($activity->subject->id, $event->id);
    }

    /** @test */
    public function it_records_activity_when_a_place_is_created()
    {
        $this->signIn();

        $place = create('App\Place');

        $this->assertDatabaseHas('activities', [
            'name' => 'created_place',
            'user_id' => $place->user_id,
            'subject_id' => $place->id,
            'subject_type' => 'App\Place'
        ]);

        $activity = \App\Activity::first();

        $this->assertEquals($activity->subject->id, $place->id);
    }

    /** @test */
    public function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        create('App\Event', ['user_id' => auth()->id()], 2);

        auth()->user()->activities()->first()->update([
            'created_at' => \Carbon\Carbon::now()->subWeek()
        ]);

        $feed = \App\Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            \Carbon\Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            \Carbon\Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
