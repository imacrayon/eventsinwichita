<?php

namespace Tests\Feature;

use App\User;
use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageEventTest extends TestCase
{
    use refreshDatabase;

    /** @test */
    public function can_create_event()
    {
        $user = create(User::class);
        $this->be($user)
            ->get(route('events.create'))
            ->assertOk();
        $this->post(
            route('events.store'),
            $attributes = make(Event::class)->toArray()
        )->assertRedirect(route('events.index'));
        $this->assertDatabaseHas(
            'events',
            ['user_id' => $user->id] + $attributes
        );
        $this->post(
            route('events.store'),
            make(Event::class, ['end' => null])->toArray()
        )->assertRedirect(route('events.index'));
    }

    /** @test */
    public function can_update_event()
    {
        $event = create(Event::class);
        $this->be($event->user)
            ->get(route('events.edit', $event))
            ->assertOk();
        $this->put(route('events.update', $event), $attributes = [
            'name' => 'Christian',
        ])->assertRedirect(route('events.edit', $event));
        $this->assertDatabaseHas('events', $attributes);
    }

    /** @test */
    public function can_delete_event()
    {
        $event = create(Event::class);
        $this->be($event->user)
            ->delete(route('events.destroy', $event))
            ->assertRedirect(route('events.edit', $event));
        $this->assertSoftDeleted('events', $event->only('id'));
    }
}
