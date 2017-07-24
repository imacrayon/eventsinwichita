<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventCommentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function unauthenticated_user_cannot_comment_on_an_event()
    {
        $this->disableExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/api/events/1/comments', []);
    }

    /** @test */
    public function authorized_user_can_comment_on_an_event()
    {
        $this->signIn();

        $event = create('App\Event');
        $comment = make('App\Comment');
        $response = $this->post('/api' . $event->url() . '/comments', $comment->toArray());
        $this->assertDatabaseHas('comments', ['body' => $comment->body]);
        $this->assertCount(1, $event->comments);
    }

    /** @test */
    function comment_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $event = create('App\Event');
        $comment = make('App\Comment', ['body' => null]);
        $this->post('/api' . $event->url() . '/comments', $comment->toArray())
             ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorized_user_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $comment = create('App\Comment');

        $this->delete("/api/comments/{$comment->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/api/comments/{$comment->id}")
            ->assertStatus(403);
    }

    /** @test */
    function authorized_user_can_delete_replies()
    {
        $this->signIn();

        $comment = create('App\Comment', ['user_id' => auth()->id()]);
        $this->delete("/api/comments/{$comment->id}")->assertStatus(204);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        $this->assertCount(0, $comment->subject->fresh()->comments);
    }

}
