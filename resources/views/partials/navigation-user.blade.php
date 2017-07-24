<li class="{{ $visibility }}">
    <a class="{{ request()->path() === "users/{$user->id}/events" ? 'active' : '' }}" href="{{ $user->url() .'/events' }}">
        <svg class="icon icon-calendar"><use xlink:href="{{ asset('/images/icons.svg#icon-calendar') }}"></use></svg>
        My Events
    </a>
</li>
<li class="{{ $visibility }}">
    <a class="{{ request()->path() === "users/{$user->id}/places" ? 'active' : '' }}" href="{{ $user->url() .'/places' }}">
        <svg class="icon icon-pin"><use xlink:href="{{ asset('/images/icons.svg#icon-pin') }}"></use></svg>
        My Places
    </a>
</li>
<li class="{{ $visibility }}">
    <a class="{{ request()->path() === "users/{$user->id}/notifications" ? 'active' : '' }}" href="{{ $user->url() .'/notifications' }}">
        <svg class="icon icon-bell"><use xlink:href="{{ asset('/images/icons.svg#icon-bell') }}"></use></svg>
        Notifications
    </a>
</li>
<li class="{{ $visibility }}">
    <a class="{{ request()->path() === "users/{$user->id}/settings" ? 'active' : '' }}" href="{{ $user->url() .'/settings' }}">
        <svg class="icon icon-cog"><use xlink:href="{{ asset('/images/icons.svg#icon-cog') }}"></use></svg>
        Settings
    </a>
</li>
<li class="{{ $visibility }}">
    <a href="{{ url('/logout') }}"
        onclick="event.preventDefault();
                document.getElementById('{{ $visibility }}-logout-form').submit();">
        <svg class="icon icon-power"><use xlink:href="{{ asset('/images/icons.svg#icon-power') }}"></use></svg>
        Logout
    </a>
    <form id="{{ $visibility }}-logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</li>
