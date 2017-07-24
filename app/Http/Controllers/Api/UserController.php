<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\EventRepository;
use App\Repositories\PlaceRepository;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $users;

    protected $events;

    protected $places;

    public function __construct(
        UserRepository $users,
        EventRepository $events,
        PlaceRepository $places
    )
    {
        $this->middleware('auth:api');

        $this->users = $users;
        $this->events = $events;
        $this->places = $places;
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
