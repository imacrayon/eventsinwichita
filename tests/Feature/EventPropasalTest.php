<?php

namespace Tests\Feature;

use App\User;
use App\Event;
use Tests\TestCase;
use App\Notifications\EventProposal;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventPropasalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_propose_event_changes()
    {
        Notification::fake();

        $this->withoutExceptionHandling();
        $admin = create(User::class, ['role' => 'admin']);
        $event = create(Event::class);
        $this->be($event->user)
            ->get(route('events.proposals.create', $event))
            ->assertOk();
        $this->post(route('events.proposals.store', $event), $attributes = [
            'name' => 'Christian',
        ])->assertRedirect(route('events.show', $event));

        Notification::assertSentTo(
            $admin,
            EventProposal::class,
            function ($notification, $channels) use ($attributes) {
                return $notification->changes === $attributes;
            }
        );
    }
}
