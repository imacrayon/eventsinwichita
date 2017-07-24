@component('mail::layout')

@slot('header')
@component('mail::header', ['url' => url('/')])
{{ config('app.name') }}
@endcomponent
@endslot

# New user registered: {{ $user->name }}

We just had another registration by {{ $user->name }} ({{ $user->id }})

@component('mail::button', ['url' => url($user->url())])
View Profile
@endcomponent

@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reverved.
@endcomponent
@endslot
@endcomponent
