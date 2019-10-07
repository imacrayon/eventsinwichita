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
        create(Event::class,['start' => now()->addDay(),'end'=>now()->addDays(2)],5);

        $response = $this->get(route('api.events.index'));

        $response->assertOk()
            ->assertJsonCount(5,'data');
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

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'start',
                    'end',
                    'timezone',
                    'location',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function cannot_view_non_existing_event()
    {
        $response = $this->get(route('api.events.show',9000));

        $response->assertNotFound()
            ->assertJson(['message' => 'Event does not exists.']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function cannot_view_deleted_event()
    {
        $event = create(Event::class,['deleted_at'=>now()]);
        $response = $this->get(route('api.events.show',$event));

        $response->assertNotFound()
            ->assertJson(['message' => 'Event does not exists.']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function cannot_view_unpublished_event()
    {
        $event = create(Event::class,['approved_at'=>null]);
        $response = $this->get(route('api.events.show',$event));

        $response->assertNotFound()
            ->assertJson(['message' => 'Event does not exists.']);
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

        $response->assertOk()
            ->assertJsonCount(10,'data');
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

        create(Event::class,['start' => $start, 'end' => $end], 10);

        $response = $this->get(route('api.events.index')."?before={$nonExistingBeforeDate}");

        $response->assertOk()
            ->assertJsonCount(0,'data');
    }
}
