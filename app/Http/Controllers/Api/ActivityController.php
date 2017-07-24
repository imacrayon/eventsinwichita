<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Filters\ActivityFilters;
use App\Http\Controllers\Controller;
use App\Repositories\ActivityRepository;

class ActivityController extends Controller
{
    /**
     * The activity repository instance.
     *
     * @var \App\Repositories\ActivityRepository
     */
    protected $activities;

    public function __construct(ActivityRepository $activities)
    {
        $this->middleware('auth:api')->except(['index', 'show']);

        $this->activities = $activities;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ActivityFilters $filters)
    {
        return response()->json($this->activities->all($filters));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
