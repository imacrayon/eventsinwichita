@extends('layouts.main')

@section('content')
<div class="head-bar" style="margin-bottom: 2rem;">
    <div class="head-bar-container">
        <h1 class="head-bar-title">
            <svg class="icon icon-bullhorn"><use xlink:href="/images/icons.svg#icon-bullhorn"></use></svg>
            About
        </h1>
    </div>
</div>

<div class="section text-center">
    <div class="grid-container">
        <div class="grid-x grid-padding-x align-center">
            <div class="cell large-8 small-order-2 large-order-1">
                <h1 class="text-primary">It's Events...In Wichita</h1>
                <p class="lead">A simple way to find awesome things happening in your city. New local events and locations are added everyday. With a growing community of contributors it's only getting better.</p>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="section text-primary text-center">
    <div class="grid-container">
        <h2>New Stuff!</h2>
        <div class="grid-x grid-padding-x grid-padding-y medium-up-2 large-up-3">
            <div class="cell">
                <svg class="icon" style="font-size: 3rem;margin-bottom:.25rem;"><use xlink:href="/images/icons.svg#icon-calendar"></use></svg>
                <h3>New Name</h3>
                <strong>eventsinwichita.com</strong><br>
                Simple. Easy to remember.
            </div>
            <div class="cell">
                <svg class="icon" style="font-size: 3rem;margin-bottom:.25rem;"><use xlink:href="/images/icons.svg#icon-eye"></use></svg>
                <h3>New Look</h3>
                Super clean and easy to use. Much red.
            </div>
            <div class="cell">
                <svg class="icon" style="font-size: 3rem;margin-bottom:.25rem;"><use xlink:href="/images/icons.svg#icon-cog"></use></svg>
                <h3>New Speeds</h3>
                The website is packing a whole new codebase, it should be snappier.
            </div>
            <div class="cell">
                <svg class="icon" style="font-size: 3rem;margin-bottom:.25rem;"><use xlink:href="/images/icons.svg#icon-pin"></use></svg>
                <h3>New Places</h3>
                Check out the <a href="{{ url('/places') }}">Places</a> page for more info on local spots.
            </div>
            <div class="cell">
                <svg class="icon" style="font-size: 3rem;margin-bottom:.25rem;"><use xlink:href="/images/icons.svg#icon-user"></use></svg>
                <h3>New Profiles</h3>
                <a href="{{ url('/login') }}">Log in</a> to quickly create new events and manage all the events you post.
            </div>
            <div class="cell">
                <svg class="icon" style="font-size: 3rem;margin-bottom:.25rem;"><use xlink:href="/images/icons.svg#icon-bell"></use></svg>
                <h3>New Features</h3>
                Comments and notifications have been added. More filtering and tagging options are on their way.
            </div>
        </div>
    </div>
</div>

<hr>

<div class="section text-center">
    <div class="grid-container grid-container-padded">
        <p><strong>Events in Wichita</strong> is maintained by <a href="http://imacrayon.com" target="_blank">Christian Taylor</a>. The website's <a href="https://linearicons.com" target="_blank">icons</a> are designed by <a href="https://perxis.com/" target="_blank">Perxis</a>.</p>
    </div>
</div>

<div class="section bg-image splash text-center">
    <div class="grid-container grid-container-padded">
        <svg class="icon" style="font-size: 3rem;margin-bottom:.25rem;"><use xlink:href="/images/icons.svg#icon-bullhorn"></use></svg>
        <h2>Help Us Out!</h2>
        <p><strong>Spread the word</strong>, help us grow our community. Make this <strong>the spot</strong> to find cool stuff going on in Wichita.</p>
    </div>
</div>
@endsection
