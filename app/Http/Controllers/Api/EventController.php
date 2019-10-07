<?php
namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Resources\EventResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * @param Request $request
     * @return EventResource
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $after = $request->get('after', today());
        $request->merge([
            'after' => $after,
            'before' => $request->get('before', (new Carbon($after))->addWeek()->startOfDay()),
        ]);

        return EventResource::collection(Event::filter($request)
            ->orderBy('start', 'asc')
            ->get());
    }


    /**
     * @param Event $event
     * @return EventResource
     */
    public function show(Event $event) {
        // abort if resource is either deleted or not approved
        abort_if($event->deleted_at !== null || $event->approved_at === null,404,'Event does not exists.');

        return new EventResource($event);
    }
}
