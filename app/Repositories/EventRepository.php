<?php

namespace App\Repositories;

use App\User;
use App\Event;
use Carbon\Carbon;
use App\Filters\EventFilters;

class EventRepository extends Repository
{
    public function __construct(Event $event, PlaceRepository $places, TagRepository $tags)
    {
        $this->model = $event;
        $this->places = $places;
        $this->tags = $tags;
    }

    protected function getTagIds($tags)
    {
        if (is_array($tags)) {
            return $tags;
        }

        return array_reduce(explode(',', $tags), function($ids, $name) {
            $tag = $this->tags->store(['name' => $name]);
            $ids[] = $tag->id;
            return $ids;
        }, []);
    }

    /**
     * Get all.
     *
     * @return Collection
     */
    public function all(EventFilters $filters)
    {
        return $this->model->filter($filters)->get();
    }

    /**
     * Get all of the events for the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser(User $user, EventFilters $filters)
    {
        return $user->events()->filter($filters)->get();
    }

    /**
     * Create or update the event.
     *
     * @param  \App\Event $event
     * @param  array  $inputs
     * @param  integer  $user_id
     * @return \App\Event
     */
    protected function save(Event $event, array $inputs, $user_id = null)
    {
        $event->fill($inputs);

        // If no end_time is set, use the end of the day.
        if ($event->start_time !== null && $event->end_time === null) {
            $event->end_time = $event->start_time->copy()->setTime(23, 59, 59);
        }

        // Attach a user
        if ($user_id) {
            $event->user_id = $user_id;
        }

        $event->save();

        $event->load('place');

        return $event;
    }

    /**
     * Create a new event for the given user and data.
     *
     * @param  array  $data
     * @return \App\Event
     */
    public function store(array $data, $user_id)
    {
        $event = $this->save(new Event, $data, $user_id);

        // Attach tags
        if (array_key_exists('tags', $data)) {
            $event->tags()->attach($this->getTagIds($data['tags']));
        }

        return $event;
    }

    /**
     * Update the event.
     *
     * @param  array  $data
     * @return \App\Event
     */
    public function update(array $data, Event $event)
    {
        $event = $this->save($event, $data);

        // Update tags
        if (array_key_exists('tags', $data)) {
            $event->tags()->sync($this->getTagIds($data['tags']));
        }

        return $event;
    }

    /**
     * Destroy the event.
     *
     * @param  Event $event
     * @return boolean
     */
    public function destroy(Event $event)
    {
        $event->delete();
    }

    public function storeOrUpdate(array $attributes, array $data, $user_id)
    {
        $event = $this->model->where($attributes)->first();
        if (!is_null($event)) {
            return $this->update($data, $event);
        }

        return $this->store($data, $user_id);
    }
}
