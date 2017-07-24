<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventsTest extends TestCase
{
    use DatabaseMigrations;

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
    function unauthorized_user_cannot_update_an_event()
    {
        $this->disableExceptionHandling();

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $this->signIn();

        $event = create('App\Event', [
            'place_id' => 3
        ]);

        $updates = [
            'name'        => 'TESTING',
            'place_id'    => 1,
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
            'place_id' => 3
        ]);

        $updates = [
            'name'        => 'TESTING',
            'place_id'    => 1,
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
