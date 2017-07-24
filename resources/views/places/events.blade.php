@extends('layouts.main')

@section('content')
  <div class="head-bar">
    <div class="head-bar-container">
      <h1 class="head-bar-title">{{ $place->name }}</h1>
    </div>
  </div>

  <div class="top-bar">
    <div class="top-bar-container">
      <div class="top-bar-left">
        <ul class="menu">
          <li>
            <a href="{{ url('/places', $place->id) }}">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-list"></use></svg>
              Details
            </a>
          </li>
          <li>
            <a href="{{ url('/places', $place->id) }}" class="active">
              <span class="badge">{{ $place->upcoming_events_count }}</span>
              Events
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <events :scope="{{ json_encode(['place_id' => $place->id, 'end_time' => '']) }}"></events>
@endsection
