<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     *
     * @return void
     */
    public function it_can_get_events()
    {
        $response = $this->get(route('api.events.index'));

        $response->assertStatus(200);

        $this->assertArrayHasKey('results', $response->json());
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_should_get_events_after_a_given_date()
    {
        $start = now()->subDays(7);

        $end = now()->addDay();

        factory(Event::class, 10)->create(['start' => $start, 'end' => $end]);

        $response = $this->get(route('api.events.index')."?after={$start}&before={$end}");

        $response->assertOk();

        $this->assertCount(10, $response->json('results'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_should_return_empty_results_if_not_event_is_found_matching_query()
    {
        $start = now()->subDays(7);

        $end = now()->addDay();

        $nonExistingBeforeDate = now()->subDays(14);

        factory(Event::class, 10)->create(['start' => $start, 'end' => $end]);

        $response = $this->get(route('api.events.index')."?before={$nonExistingBeforeDate}");

        $response->assertOk();

        $this->assertCount(0, $response->json('results'));
    }
}
