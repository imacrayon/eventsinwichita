@extends('users.profile')

@section('profile-content')
    <events :scope="{{ json_encode(['user_id' => $user->id, 'end_time' => 0]) }}"  style="margin-bottom: 2rem;"></events>
@endsection
