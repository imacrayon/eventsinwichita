@component('mail::layout')

@slot('header')
@component('mail::header', ['url' => url('/')])
{{ config('app.name') }}
@endcomponent
@endslot

# New place created: {{ $place->name }} ({{ $place->id }})

{{ $place->name }}<br>
{{ $place->street }} {{ $place->city}}, {{ $place->state}} {{ $place->zip }}

@component('mail::button', ['url' => url($place->url())])
View Place
@endcomponent

@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reverved.
@endcomponent
@endslot
@endcomponent
