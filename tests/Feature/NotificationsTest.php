<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    function user_can_fetch_their_unread_notifications()
    {
        create(DatabaseNotification::class);

        $response = $this->getJson('/api/users/' . auth()->id() . '/notifications')->json();

        $this->assertCount(1, $response['data']);
    }
    /** @test */
    function user_can_mark_a_notification_as_read()
    {
        create(DatabaseNotification::class);

        $user = auth()->user();
        $this->assertCount(1, $user->unreadNotifications);
        $this->delete("/api/users/{$user->id}/notifications/" . $user->unreadNotifications->first()->id);
        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }

}
