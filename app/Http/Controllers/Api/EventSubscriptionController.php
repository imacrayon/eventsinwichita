<?php

namespace App\Http\Controllers\Api;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Event $event)
    {
        return response()->json($event->subscribe());
    }

    /**
     * Delete an existing event subscription.
     *
     * @param int    $channelId
     * @param Thread $thread
     */
    public function destroy(Event $event)
    {
        $event->unsubscribe();

        return response()->json([], 204);
    }
}
