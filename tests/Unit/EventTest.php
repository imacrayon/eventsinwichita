<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_a_place()
    {
        $event = create('App\Event');

        $this->assertInstanceOf('App\Place', $event->place);
    }

    /** @test */
    public function it_has_a_user()
    {
        $event = create('App\Event');

        $this->assertInstanceOf('App\User', $event->user);
    }
}
