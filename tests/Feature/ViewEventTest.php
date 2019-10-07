<?php

namespace Tests\Feature;

use App\Event;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEventTest extends TestCase
{
    use refreshDatabase;

    /** @test */
    public function can_view_all_events()
    {
        $this->withoutExceptionHandling();
        $now = create(Event::class, ['start' => now()]);
        $oneHour = create(Event::class, ['start' => now()->addHour(1)]);
        $pastEvent = create(Event::class, ['end' => new Carbon('yesterday')]);
        $futureEvent = create(Event::class, ['start' => new Carbon('next month')]);

        $this->get(route('events.index'))
            ->assertOk()
            ->assertPropCount('events', 2)
            ->assertPropValue('events', function ($events) {
                $this->assertEquals(
                    ['id', 'name', 'start', 'end', 'timezone', 'location'],
                    array_keys($events[0])
                );
            })
            ->assertSeeInOrder([$now->name, $oneHour->name])
            ->assertDontSee($pastEvent->name)
            ->assertDontSee($futureEvent->name);
    }

    /** @test */
    public function can_view_event()
    {
        $event = create(Event::class);
        $this->get(route('events.show', $event))
            ->assertOk()
            ->assertPropValue('event', function ($event) {
                $this->assertEquals(
                    ['id', 'name', 'start', 'end', 'timezone', 'location', 'description', 'deleted_at', 'sources', 'can'],
                    array_keys($event)
                );
            })
            ->assertPropValue('event.name', $event->name);
    }
}
