<?php

namespace Tests;

use App\Exceptions\Handler;
use Laravel\Passport\Passport;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

// TODO: In PHP 7 this can be a anonymous class
class TestingHandler extends Handler {
    public function __construct() {}
    public function report(\Exception $e) {}
    public function render($request, \Exception $e) {
        throw $e;
    }
}

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        $this->disableExceptionHandling();
    }

    protected function signIn($user = null)
    {
        $user = $user ?: factory('App\User')->create();
        Passport::actingAs($user);

        return $this;
    }

    // Hat tip, @adamwathan.
    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->instance(ExceptionHandler::class, new TestingHandler);
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
}
