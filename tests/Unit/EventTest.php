<?php

namespace Tests\Unit;

use App\Event;
use App\Source;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $event = create(Event::class);

        $this->assertEquals($event->user->id, $event->user_id);
    }

    /** @test */
    public function it_can_have_sources()
    {
        $event = create(Event::class);

        $source = $event->sources()->create(make(Source::class)->toArray());

        $this->assertCount(1, $event->sources);
        $this->assertTrue($event->sources->contains($source));
    }
}
