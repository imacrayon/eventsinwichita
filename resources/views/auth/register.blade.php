@extends('layouts.app')

@section('main')
<div class="grid-x grid-padding-x splash-image">
    <div class="cell">

        <div class="card">
            <div class="card-head">
                <h1 class="card-title">Register a New Account</h1>
            </div>
            <div class="card-section">
                <p>Already have an account? <a href="{{ url('/login') }}">Login</a>.</p>

                <hr>

                <a class="button small expanded" style="background-color: #3b5999;" href="{{ url('/auth/facebook')}}" data-tooltip="Login with Facebook" tabindex="6">
                    <svg class="icon" style="font-size: 1.25em;vertical-align: bottom;"><use xlink:href="{{ asset('/images/icons.svg#icon-facebook') }}"></use></svg>
                    Register with Facebook
                </a>

                <hr>

                <form role="form" method="POST" action="{{ url('/register') }}">
                    {{ csrf_field() }}

                    <label for="name" class="{{ $errors->has('name') ? 'is-invalid-label' : '' }}">Name
                        <input type="text" name="name" value="{{ old('name') }}" class="{{ $errors->has('name') ? ' is-invalid-input' : '' }}" required autofocus>
                        @if ($errors->has('name'))<span class="form-error{{ $errors->has('name') ? ' is-visible' : '' }}">{{ $errors->first('name') }}</span>@endif
                    </label>

                    <label class="{{ $errors->has('email') ? 'is-invalid-label' : '' }}">Email
                        <input type="email" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? ' is-invalid-input' : '' }}" required>
                        <p class="help-text">Your Email will <strong>NOT</strong> be made public.</p>
                        @if ($errors->has('email'))<span class="form-error{{ $errors->has('email') ? ' is-visible' : '' }}">{{ $errors->first('email') }}</span>@endif
                    </label>

                    <label class="{{ $errors->has('password') ? 'is-invalid-label' : '' }}">Password
                        <input type="password" name="password" class="{{ $errors->has('password') ? ' is-invalid-input' : '' }}" required>
                        @if ($errors->has('password'))<span class="form-error{{ $errors->has('password') ? ' is-visible' : '' }}">{{ $errors->first('password') }}</span>@endif
                    </label>

                    <label class="{{ $errors->has('password_confirmation') ? 'is-invalid-label' : '' }}">Confirm Password
                        <input type="password" name="password_confirmation" class="{{ $errors->has('password_confirmation') ? 'is-invalid-input' : '' }}" required>
                        @if ($errors->has('password_confirmation'))<span class="form-error{{ $errors->has('password_confirmation') ? ' is-visible' : '' }}">{{ $errors->first('password_confirmation') }}</span>@endif
                    </label>

                    <button class="button expanded" style="margin: 0;">
                        Register
                    </button>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
