<?php

namespace App\Http\Controllers;

use App\User;
use App\Event;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Notifications\EventProposal;
use Illuminate\Support\Facades\Redirect;

class EventProposalController extends Controller
{
    public function create(Request $request, Event $event)
    {
        return Inertia::render('Events/Proposals/Create', [
            'locations' => Event::distinct('location')->pluck('location'),
            'event' => $event->only([
                'id',
                'name',
                'start',
                'end',
                'timezone',
                'location',
                'description',
                'deleted_at',
            ]) + ['can' => [
                'update' => optional($request->user())->can('update', $event) ?? false,
            ]],
        ]);
    }

    public function store(Request $request, Event $event)
    {
        $attributes = $request->validate([
            'name' => ['nullable', 'max:255'],
            'start' => ['nullable', 'date', 'after:yesterday'],
            'end' => ['nullable', 'date', 'after:start'],
            'location' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:5000'],
        ]);

        $changes = array_diff(
            $attributes,
            $event->only(array_keys($attributes))
        );

        if (! count($changes)) {
            return Redirect::back()->with('error', 'Nothing was changed.');
        }

        User::admins()->get()->each->notify(new EventProposal($event, $changes));

        return Redirect::route('events.show', $event)
            ->with('success', 'Change request sent.');
    }
}
