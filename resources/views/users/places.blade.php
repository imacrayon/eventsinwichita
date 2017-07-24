@extends('users.profile')

@section('profile-content')
    <places :filters="{{ json_encode(['user_id' => $user->id]) }}"  style="margin-bottom: 2rem;"></places>
@endsection
