<?php
namespace App\Http\Controllers\Api;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $after = $request->get('after', today());
        $request->merge([
            'after' => $after,
            'before' => $request->get('before', (new Carbon($after))->addWeek()->startOfDay()),
        ]);

        return response()->json([
            'results' => Event::filter($request)
                ->orderBy('start', 'asc')
                ->get()
                ->transform(function ($event) {
                    return $event->only(['id', 'name', 'start', 'end', 'timezone', 'location']);
                })
        ]);
    }
}
