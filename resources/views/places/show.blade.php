@extends('layouts.main')

@section('content')
    <place-page :attributes="{{ json_encode($place) }}"></place-page>
@endsection
