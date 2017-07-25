@component('mail::layout')

@slot('header')
@component('mail::header', ['url' => url('/')])
{{ config('app.name') }}
@endcomponent
@endslot

# Welcome to Events in Wichita {{ $user->name }}
We are so excited you joined Events in Wichita. Here are a couple of tips to help you get started:

## Add Events
Add new events right from the [homepage]({{ url('/') }}). Just click the "**Add**" button at the top of the page.

## Watch Events
Click the "**Watch**" button at the top of an event's page to get email notifications when the event is updated.

## Give Feedback & Ask Questions
We want to make this site the best it can be! If you have questions or want to offer feedback stop by our [contact page]({{ url('/contact') }}).

@component('mail::panel')
## Are you the owner of a venue listed on our website?
Please [contact us]({{ url('/contact') }}) and we will give you access to edit your venue's information.
@endcomponent

@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reverved.

If you wish to adjust your email settings, go <a href="{{ url($user->url() . '/settings') }}">here</a>. If you want to turn off all email notifications from {{ config('app.name') }}, go <a href="%unsubscribe_url%">here</a> (use with care!). Questions? Contact {{ config('app.name') }} through the <a href="{{ url('/contact') }}">contact form</a>.
@endcomponent
@endslot
@endcomponent
