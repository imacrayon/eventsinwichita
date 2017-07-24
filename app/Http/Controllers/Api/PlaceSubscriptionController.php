<?php

namespace App\Http\Controllers\Api;

use App\Place;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlaceSubscriptionController extends Controller
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
    public function store(Request $request, Place $place)
    {
        return response()->json($place->subscribe());
    }

    /**
     * Delete an existing place subscription.
     *
     * @param int    $channelId
     * @param Thread $thread
     */
    public function destroy(Place $place)
    {
        $place->unsubscribe();

        return response()->json([], 204);
    }
}
