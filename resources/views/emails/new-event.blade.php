@component('mail::layout')

@slot('header')
@component('mail::header', ['url' => url('/')])
{{ config('app.name') }}
@endcomponent
@endslot

# New event created: {{ $event->name }} ({{ $event->id }})

{{ $event->name }}<br>
{{ $event->start_time->format('g:ia, m/d/Y') }} {{ isset($event->end_time) ?  ' - '.$event->end_time->format('g:ia, m/d/Y') : '' }}

{{ $event->place->name }}

{!! nl2br(e($event->description)) !!}

@component('mail::button', ['url' => url($event->url())])
View Event
@endcomponent

@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reverved.
@endcomponent
@endslot
@endcomponent
