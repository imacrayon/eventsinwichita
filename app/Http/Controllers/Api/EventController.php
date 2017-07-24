<?php

namespace App\Http\Controllers\Api;

use App\Event;
use Illuminate\Http\Request;
use App\Filters\EventFilters;
use App\Http\Controllers\Controller;
use App\Repositories\EventRepository;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    /**
     * The event repository instance.
     *
     * @var \App\Repositories\EventRepository
     */
    protected $events;

    public function __construct(EventRepository $events)
    {
        $this->middleware('auth:api')->except(['index', 'show']);

        $this->events = $events;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Filters\EventFilters  $filters
     * @return \Illuminate\Http\Response
     */
    public function index(EventFilters $filters)
    {
        return response()->json($this->events->all($filters));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        return response()->json(
            $this->events->store($request->all(), $request->user()->getKey())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event->load('user', 'place', 'tags', 'comments.user');

        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        return response()->json($this->events->update($request->all(), $event));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $this->authorize('destroy', $event);

        $this->events->destroy($event);

        return response()->json([], 204);
    }
}
