<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_has_a_user()
    {
        $comment = create('App\Comment');
        $this->assertInstanceOf('App\User', $comment->user);
    }
}
