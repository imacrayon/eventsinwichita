@extends('layouts.app')

@section('main')
<?php $authenticated = !auth()->guest(); ?>
<?php $user = auth()->user(); ?>
<header class="head">
    <div class="title-bar">
        <a href="{{ url('/') }}">
            <div class="title-bar-title">{{ config('app.name') }}</div>
        </a>
        <button class="title-bar-toggle"
            onclick="document.body.classList.toggle('js-navigation-open');">
            <span class="toggle-lines"></span>
        </button>
    </div>
    <div class="top-bar navigation">
        <div class="top-bar-container">
            <div class="top-bar-left">
                <ul class="menu">
                    <li>
                        <a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">
                            Events in Wichita
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/places') }}" class="{{ Request::is('places') ? ' active' : '' }}">
                            <svg class="icon icon-pin show-for-small-only"><use xlink:href="{{ asset('/images/icons.svg#icon-pin') }}"></use></svg>
                            Places
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="{{ Request::is('contact') ? ' active' : '' }}">
                            <svg class="icon icon-mail show-for-small-only"><use xlink:href="{{ asset('/images/icons.svg#icon-mail') }}"></use></svg>
                            Contact Us
                        </a>
                    </li>
                </ul>
            </div>
            <div class="top-bar-right">
                <ul class="menu">
                    @if ($authenticated)
                        <?php $viewingProfile = request()->is("users/{$user->id}*"); ?>
                        <li class="show-for-small-only">
                            <a class="profile{{ $viewingProfile  ? ' active' : '' }}" href="{{ $user->url() }}">
                                <div class="profile-section">
                                    <img class="profile-image" src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" width="32" height="32">
                                </div>
                                <div class="profile-section">
                                    <span class="profile-title">{{ auth()->user()->name }}</span>
                                </div>
                            </a>
                        </li>
                        <li class="hide-for-small-only">
                            <notifications-dropdown></notifications-dropdown>
                        </li>
                        @include('partials.navigation-user', ['visibility' => 'show-for-small-only'])
                        <li class="hide-for-small-only">
                            <dropdown size="small">
                                <a class="profile dropdown{{ $viewingProfile ? ' active' : '' }}">
                                    <div class="profile-section">
                                        <img class="profile-image" src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" width="32" height="32">
                                    </div>
                                    <div class="profile-section main-section">
                                        <span class="profile-title">{{ auth()->user()->name }}</span>
                                    </div>
                                </a>
                                <ul v-cloak slot="menu" class="vertical menu">
                                    <li>
                                        <a href="{{ $user->url() }}" class="{{ request()->path() === "users/{$user->id}" ? 'active' : '' }}">
                                            <svg class="icon icon-user"><use xlink:href="{{ asset('/images/icons.svg#icon-user') }}"></use></svg>
                                            Profile
                                        </a>
                                    </li>
                                    @include('partials.navigation-user', ['visibility' => ''])
                                </ul>
                            </dropdown>
                        </li>
                    @else
                        <li>
                            <a href="{{ url('/login') }}">
                                <svg class="icon icon-power"><use xlink:href="{{ asset('/images/icons.svg#icon-power') }}"></use></svg>
                                Login
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</header>
<main class="main">
    @yield('content')
</main>
<footer class="foot">
    <div class="grid-container grid-container-padded">
        <div class="grid-x grid-margin-x">
            <div class="cell shrink">
                &copy; {{ date('Y') }} <a href="{{ url('/') }}">{{ config('app.name') }}</a>
            </div>
        </div>
    </div>
</footer>
@endsection
