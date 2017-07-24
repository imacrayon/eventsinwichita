<?php

namespace App\Http\Controllers\Api;

use App\Place;
use Illuminate\Http\Request;
use App\Filters\PlaceFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeocodeRequest;
use App\Repositories\PlaceRepository;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;

class PlaceController extends Controller
{
    /**
     * The place repository instance.
     *
     * @var \App\Repositories\PlaceRepository
     */
    protected $places;

    public function __construct(PlaceRepository $places)
    {
        $this->middleware('auth:api')->except(['index', 'show']);

        $this->places = $places;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Filters\PlaceFilters  $filters
     * @return \Illuminate\Http\Response
     */
    public function index(PlaceFilters $filters)
    {
        return response()->json($this->places->all($filters));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePlaceRequest $request)
    {
        return response()->json(
            $this->places->store($request->all(), $request->user()->getKey())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        $place->load('user', 'events.tags', 'tags', 'comments.user');

        return response()->json($place);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlaceRequest $request, Place $place)
    {
        $this->authorize('update', $place);

        return response()->json($this->places->update($request->all(), $place));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        $this->authorize('destroy', $place);

        $this->places->destroy($place);

        return response()->json([], 204);
    }

    /**
     * Geocode request data into a Place.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function geocode(GeocodeRequest $request)
    {
        return response()->json(
            $this->places->findOrNew($request->all())
                         ->load('tags')
        );
    }
}
