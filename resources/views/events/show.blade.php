@extends('layouts.main')

@section('content')
    <event-page :attributes="{{ json_encode($event) }}"></event-page>
@endsection
