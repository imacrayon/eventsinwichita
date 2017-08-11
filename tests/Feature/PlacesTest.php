<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlacesTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->place = create('App\Place');
    }

    /** @test */
    public function user_can_view_all_places()
    {
        $this->get('/places')
            ->assertStatus(200);

        $this->get('/api/places')
            ->assertStatus(200);
    }

    /** @test */
    public function user_can_view_a_single_place()
    {
        $response = $this->get("/places/{$this->place->id}");

        $response->assertSee($this->place->name);
    }

    /** @test */
    function unauthenticated_user_cannot_create_a_place()
    {
        $this->disableExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/api/places', []);
    }

    /** @test */
    function authenticated_user_can_create_a_place()
    {
        $this->signIn();

        $place = make('App\Place');

        $response = $this->post('/api/places', $place->toArray())
                         ->decodeResponseJson();

        $this->get("/places/{$response['id']}")
             ->assertSee($place->name);
    }

    /** @test */
    function user_can_filter_places_by_user_id()
    {
        $user = create('App\User');

        $placebyUser = create('App\Place', ['user_id' => $user->id]);

        $placeNotByUser = create('App\Place');

        $this->get('/api/places?user_id=' . urlencode($user->id))
             ->assertJsonFragment(['name' => $placebyUser->name])
             ->assertJsonMissing(['name' => $placeNotByUser->name]);
    }

    /** @test */
    function unauthenticated_user_cannot_geocode()
    {
        $this->disableExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/api/geocode', []);
    }


    /** @test */
    function authenticated_user_can_geocode_by_name()
    {
        $this->signIn();

        $this->post('/api/geocode', ['name' => 'Wichita KS'])
            ->assertStatus(200)
            ->assertJson([
                'name' => 'Wichita KS',
                'street' => '',
                'city' => 'Wichita',
                'state' => 'KS',
                'zip' => ''
            ]);
    }

    /** @test */
    function authenticated_user_can_geocode_by_facebook_id()
    {
        $this->signIn();

        $place = create('App\Place', [
            'facebook_id' => 123
        ]);

        $this->post('/api/geocode', ['facebook_id' => $place->facebook_id])
            ->assertStatus(200)
            ->assertJson($place->attributesToArray());
    }
}
