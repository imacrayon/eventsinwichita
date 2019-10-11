<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\Schema;
use Illuminate\Support\Facades\Redirect;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $after = $request->input('after', today());
        $before = $request->input('before', (new Carbon($after))->addWeek()->startOfDay());
        $trashed = $request->input('trashed');
        $unapproved = $request->has('unapproved');

        $filter = collect([
            'after' => $after,
            'before' => $before,
            'trashed' => $trashed,
            'unapproved' => $unapproved,
        ]);

        if ($request->hasAny('trashed', 'unapproved')) {
            $filter = $filter->except(['after', 'before']);
        }

        $request->merge($filter->filter()->all());

        return Inertia::render('Events/Index', [
            'events' => Event::filter($request)
                ->orderBy('start', 'asc')
                ->get()
                ->transform(function ($event) {
                    return $event->only(['id', 'name', 'start', 'end', 'timezone', 'location']);
                }),
            'dates' => Event::get(['start'])->pluck('start'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Events/Create', [
            'locations' => Event::distinct('location')->pluck('location'),
        ]);
    }

    public function store(Request $request)
    {
        $event = Event::make($request->validate([
            'name' => ['required', 'max:255'],
            'start' => ['required', 'date', 'after:yesterday'],
            'end' => ['nullable', 'date', 'after:start'],
            'timezone' => ['required', 'timezone'],
            'location' => ['required', 'max:255'],
            'description' => ['nullable', 'max:5000'],
        ]))->user()->associate($request->user());

        if ($request->user()) {
            $event->approve();
        }

        $event->save();

        return Redirect::route('events.index')->with('success', 'Event created.');
    }

    public function show(Request $request, Event $event)
    {
        return Inertia::render('Events/Show', [
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'start' => $event->start,
                'end' => $event->end,
                'timezone' => $event->timezone,
                'location' => $event->location,
                'description' => $event->html,
                'deleted_at' => $event->deleted_at,
                'sources' => $event->sources->transform(function ($source) {
                    return ['url' => $source->url];
                }),
                'can' => [
                    'update' => optional($request->user())->can('update', $event) ?? false,
                ],
            ],
        ])->withViewData([
            'meta' => [
                'title' => $event->name,
                'description' => Str::limit(strip_tags($event->html), 137, '...'),
                'og:title' => $event->name,
                'og:url' => route('events.show', $event),
                'og:type' => 'article',
                'article:published_time' => $event->created_at->toIso8601String(),
                'article:modified_time' => $event->updated_at->toIso8601String(),
                'article:expiration_time' => $event->end->toIso8601String(),
            ],
            'schema' => Schema::event()
                ->name($event->name)
                ->about($event->html)
                ->doorTime($event->start)
                ->startDate($event->start)
                ->endDate($event->end)
                ->location($event->location)
                ->url(route('events.show', $event)),
        ]);
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        return Inertia::render('Events/Edit', [
            'event' => $event->only([
                'id',
                'name',
                'start',
                'end',
                'timezone',
                'location',
                'description',
                'deleted_at',
            ]),
            'locations' => Event::distinct('location')->pluck('location'),
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $event = tap($event)->update($request->validate([
            'name' => ['required', 'max:255'],
            'start' => ['nullable', 'date', 'after:yesterday'],
            'end' => ['nullable', 'date', 'after:start'],
            'timezone' => ['nullable', 'timezone'],
            'location' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:5000'],
        ]));

        return Redirect::route('events.edit', $event)->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('update', $event);

        $event->delete();

        return Redirect::route('events.edit', $event)->with('success', 'Event deleted.');
    }

    public function restore(Event $event)
    {
        $this->authorize('update', $event);

        $event->restore();

        return Redirect::route('events.edit', $event)->with('success', 'Event restored.');
    }
}
