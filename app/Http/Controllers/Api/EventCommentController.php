<?php

namespace App\Http\Controllers\Api;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class EventCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Event $event
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        return response()->json($event->comments()->with('user')->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request, Event $event)
    {
        $comment = $event->addComment([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return response()->json($comment->load('user'));
    }
}
