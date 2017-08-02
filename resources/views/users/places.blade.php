@extends('users.profile')

@section('profile-content')
    <places :scope="{{ json_encode(['user_id' => $user->id]) }}"  style="margin-bottom: 2rem;"></places>
@endsection
