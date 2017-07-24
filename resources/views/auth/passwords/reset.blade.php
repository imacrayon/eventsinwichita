@extends('layouts.app')

@section('main')
<div class="grid-x grid-padding-x splash-image">
    <div class="cell">

        <div class="card">
            <div class="card-head">
                <h2 class="card-title">Password Reset</h2>
            </div>
            <div class="card-section">
                @if (session('status'))
                    <div class="callout success">
                        {{ session('status') }}
                    </div>
                @endif

                <form role="form" method="POST" action="{{ url('/password/reset') }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <label class="{{ $errors->has('email') ? 'is-invalid-label' : '' }}">Email
                        <input type="email" name="email" value="{{ $email or old('email') }}" class="{{ $errors->has('email') ? 'is-invalid-input' : '' }}" required autofocus>
                        @if ($errors->has('email'))<span class="form-error{{ $errors->has('email') ? ' is-visible' : '' }}">{{ $errors->first('email') }}</span>@endif
                    </label>

                    <label class="{{ $errors->has('password') ? 'is-invalid-label' : '' }}">Password
                        <input type="password" name="password" class="{{ $errors->has('password') ? 'is-invalid-input' : '' }}" required>
                        @if ($errors->has('password'))<span class="form-error{{ $errors->has('password') ? ' is-visible' : '' }}">{{ $errors->first('password') }}</span>@endif
                    </label>

                    <label class="{{ $errors->has('password_confirmation') ? 'is-invalid-label' : '' }}">Confirm Password
                        <input type="password" name="password_confirmation" class="{{ $errors->has('password_confirmation') ? 'is-invalid-input' : '' }}" required>
                        @if ($errors->has('password_confirmation'))<span class="form-error{{ $errors->has('password_confirmation') ? ' is-visible' : '' }}">{{ $errors->first('password_confirmation') }}</span>@endif
                    </label>

                    <button type="submit" class="button expanded" style="margin: 0;">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
