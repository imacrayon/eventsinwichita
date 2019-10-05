<?php

namespace Tests\Feature;

use App\Event;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        $this->assertArrayHasKey('data', $response->json());
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_can_get_specified_events()
    {
        $event = create(Event::class);
        $response = $this->get(route('api.events.show',$event));

        $response->assertStatus(200);

        $this->assertArrayHasKey('data', $response->json());
    }

    /**
     * @test
     *
     * @return void
     */
    public function cannot_get_deleted_events()
    {
        $this->expectException(NotFoundHttpException::class);

        $event = create(Event::class,['deleted_at'=>now()]);
        $response = $this->get(route('api.events.show',$event));

        $response->assertStatus(404);

        $this->assertArrayHasKey('data', $response->json());
    }

    /**
     * @test
     *
     * @return void
     */
    public function cannot_get_unpublished_events()
    {
        $this->expectException(NotFoundHttpException::class);

        $event = create(Event::class,['approved_at'=>null]);
        $response = $this->get(route('api.events.show',$event));

        $response->assertStatus(404);

        $this->assertArrayHasKey('data', $response->json());
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

        $this->assertCount(10, $response->json('data'));
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

        $this->assertCount(0, $response->json('data'));
    }
}
