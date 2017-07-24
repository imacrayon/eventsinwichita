<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscriptionsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_subscribe_to_events()
    {
        $this->signIn();
        $event = create('App\Event');
        $this->post('/api' . $event->url() . '/subscriptions');
        $this->assertCount(2, $event->fresh()->subscriptions);
    }

    /** @test */
    public function user_can_unsubscribe_from_events()
    {
        $this->signIn();
        $event = create('App\Event');
        $event->subscribe();
        $this->delete('/api' . $event->url() . '/subscriptions');
        $this->assertCount(1, $event->subscriptions);
    }

    /** @test */
    public function user_can_subscribe_to_places()
    {
        $this->signIn();
        $place = create('App\Place');
        $this->post('/api' . $place->url() . '/subscriptions');
        $this->assertCount(2, $place->fresh()->subscriptions);
    }

    /** @test */
    public function user_can_unsubscribe_from_places()
    {
        $this->signIn();
        $place = create('App\Place');
        $place->subscribe();
        $this->delete('/api' . $place->url() . '/subscriptions');
        $this->assertCount(1, $place->subscriptions);
    }
}
