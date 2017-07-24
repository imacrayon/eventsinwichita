@extends('layouts.main')

@section('content')
    <places-page :query="{{ json_encode(Request::query()) }}"></places-page>
@endsection
