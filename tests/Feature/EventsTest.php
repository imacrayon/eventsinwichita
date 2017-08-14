<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventsTest extends TestCase
{
    use DatabaseMigrations;

    protected function storeEvent($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $event = make('App\Event', $overrides);

        return $this->post('/api/events', $event->toArray());
    }

    /** @test */
    public function user_can_view_all_events()
    {
        $this->get('/events')
            ->assertStatus(200);

        $this->get('/api/events')
            ->assertStatus(200);
    }

    /** @test */
    public function user_can_view_a_single_event()
    {
        $event = create('App\Event');

        $response = $this->get("/events/{$event->id}");

        $response->assertSee($event->name);
    }

    /** @test */
    function unauthenticated_user_cannot_create_an_event()
    {
        $this->disableExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/api/events', []);
    }

    /** @test */
    function authenticated_user_can_create_an_event()
    {
        $this->signIn();

        $event = make('App\Event');

        $response = $this->post('/api/events', $event->toArray())
                         ->decodeResponseJson();

        $this->get("/events/{$response['id']}")
             ->assertSee($event->name);
    }

    /** @test */
    function an_event_requires_a_name()
    {
        $this->storeEvent(['name' => null])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    function an_events_facebook_id_must_be_unique()
    {
        $event = create('App\Event');

        $this->storeEvent([
                'name'        => 'Another Event',
                'start_time' => $event->start_time->toDateTimeString(),
                'facebook_id' => $event->facebook_id
            ])
            ->assertSessionHasErrors('facebook_id');
    }

    /** @test */
    function an_events_meetup_id_must_be_unique()
    {
        $event = create('App\Event');

        $this->storeEvent([
                'name'        => 'Another Event',
                'start_time' => $event->start_time->toDateTimeString(),
                'meetup_id' => $event->meetup_id
            ])
            ->assertSessionHasErrors('meetup_id');
    }

    /** @test */
    function duplicate_events_cannot_be_created()
    {
        $this->signIn();

        $event = make('App\Event');

        $this->post('/api/events', $event->toArray());

        $this->post('/api/events', [
            'name'       => $event->name,
            'start_time' => $event->start_time->toDateTimeString(),
            'place_id'   => $event->place_id
        ]);

        $this->assertEquals(1, \App\Event::count());
    }

    /** @test */
    function unauthorized_user_cannot_update_an_event()
    {
        $this->disableExceptionHandling();

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $this->signIn();

        $event = create('App\Event');

        $updates = [
            'name'        => 'TESTING',
            'place_id'    => 120,
            'start_time'  => '2017-05-19 06:08:08',
            'description' => 'TESTING'
        ];

        $this->put("/api/events/{$event->id}", $updates);
    }

    /** @test */
    public function authorized_user_can_update_an_event()
    {
        $this->signIn();

        $event = create('App\Event', [
            'user_id'  => auth()->id(),
        ]);

        $updates = [
            'name'        => 'TESTING',
            'place_id'    => 120,
            'start_time'  => '2017-05-19 06:08:08',
            'description' => 'TESTING'
        ];

        $this->put("/api/events/{$event->id}", $updates);
        $this->assertDatabaseHas('events', $updates);
    }

    /** @test */
    function unauthorized_users_cannot_delete_events()
    {
        $this->disableExceptionHandling();

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $this->signIn();

        $event = create('App\Event');

        $this->delete("/api/events/{$event->id}");
    }

    /** @test */
    function authorized_users_can_delete_events()
    {
        $this->signIn();

        $event = create('App\Event', ['user_id' => auth()->id()]);

        $comment = create('App\Comment', ['subject_id' => $event->id]);

        $response = $this->json('DELETE', "/api/events/{$event->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('events', ['id' => $event->id])
            ->assertDatabaseMissing('comments', ['id' => $comment->id])
            ->assertEquals(0, \App\Comment::count());
    }
}
