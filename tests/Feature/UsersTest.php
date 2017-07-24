<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function unauthenticated_user_cannot_edit_settings()
    {
        $this->disableExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->get('users/1/settings');
    }

    /** @test */
    function authenticated_user_can_edit_settings()
    {
        $this->signIn();

        $this->get(auth()->user()->url() . '/settings')
            ->assertStatus(200)
            ->assertSee(auth()->user()->name);
    }

    /** @test */
    function user_can_view_user_profile()
    {
        $user = create('App\User');

        $this->get("/users/{$user->id}")
            ->assertStatus(200)
            ->assertSee($user->name);
    }
}
