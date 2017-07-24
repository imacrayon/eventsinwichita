<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlaceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_events()
    {
        $place = create('App\Place');

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $place->events);
    }

    /** @test */
    public function it_has_a_user()
    {
        $place = create('App\Place');

        $this->assertInstanceOf('App\User', $place->user);
    }
}
