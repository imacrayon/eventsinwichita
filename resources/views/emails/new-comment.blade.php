@component('mail::layout')

@slot('header')
@component('mail::header', ['url' => url('/')])
{{ config('app.name') }}
@endcomponent
@endslot

<strong>{{ $comment->user->name }}</strong> commented on <a href="{{ url($comment->url()) }}">{{ $resource->name }}</a>.

@component('mail::panel')
{{ $comment->body }}
@endcomponent

@component('mail::button', ['url' => url($comment->url()), 'color' => 'red'])
View Comment
@endcomponent

@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reverved.

If you wish to adjust your email settings, go <a href="{{ url($comment->user->url() . '/settings') }}">here</a>. If you want to turn off all email notifications from {{ config('app.name') }}, go <a href="%unsubscribe_url%">here</a> (use with care!). Questions? Contact {{ config('app.name') }} through the <a href="{{ url('/contact') }}">contact form</a>.
@endcomponent
@endslot
@endcomponent
