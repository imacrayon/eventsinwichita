@extends('users.profile')

@section('profile-content')
    <activities :filters="{{ json_encode(['user_id' => $user->id, 'limit' => 50]) }}"  style="margin-bottom: 2rem;"></activities>
@endsection
