@extends('layouts.main')

@section('content')
    <div class="head-bar">
        <div class="head-bar-container">
            <div class="media-object head-bar-title align-middle profile">
                <div class="media-object-section">
                    <img class="profile-image" src="{{ $user->avatar }}" alt="{{ $user->name }}" width="48" height="48">
                </div>
                <div class="media-object-section main-section">
                    <h1 class="profile-title">{{ $user->name }}</h1>
                    <div class="profile-meta">
                        Member since {{ $user->created_at->format('m/d/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="top-bar">
        <div class="top-bar-container">
            <div class="top-bar-left">
                <ul class="menu">
                    <li>
                        <a href="{{ $user->url() }}" class="{{ request()->path() === "users/{$user->id}" ? 'active' : '' }}">
                            <svg class="icon icon-user"><use xlink:href="{{ asset('/images/icons.svg#icon-user') }}"></use></svg>
                            Activity
                        </a>
                    </li>
                    <li>
                        <a href="{{ $user->url() . '/events' }}" class="{{ request()->path() === "users/{$user->id}/events" ? 'active' : '' }}">
                            <svg class="icon icon-calendar"><use xlink:href="{{ asset('/images/icons.svg#icon-calendar') }}"></use></svg>
                            Events
                        </a>
                    </li>
                    <li>
                        <a href="{{ $user->url() . '/places' }}" class="{{ request()->path() === "users/{$user->id}/places" ? 'active' : '' }}">
                            <svg class="icon icon-pin"><use xlink:href="{{ asset('/images/icons.svg#icon-pin') }}"></use></svg>
                            Places
                        </a>
                    </li>
                </ul>
            </div>
            @if (auth()->id() === $user->id)
                <div class="top-bar-right">
                    <ul class="menu">
                        <li>
                            <a href="{{ $user->url() . '/notifications' }}" class="{{ request()->path() === "users/{$user->id}/notifications" ? 'active' : '' }}">
                                <svg class="icon icon-bell"><use xlink:href="{{ asset('/images/icons.svg#icon-bell') }}"></use></svg>
                                Notifications
                            </a>
                        </li>
                        <li>
                            <a href="{{ $user->url() . '/settings' }}" class="{{ request()->path() === "users/{$user->id}/settings" ? 'active' : '' }}">
                                <svg class="icon icon-cog"><use xlink:href="{{ asset('/images/icons.svg#icon-cog') }}"></use></svg>
                                Settings
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    @yield('profile-content')
@endsection
