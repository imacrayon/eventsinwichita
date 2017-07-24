<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EventRepository;

class HomeController extends Controller
{
    /**
     * The event repository instance.
     *
     * @var \App\Repositories\EventRepository
     */
    protected $events;

    public function __construct(EventRepository $events)
    {
        $this->events = $events;
    }
}
