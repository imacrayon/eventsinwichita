@extends('layouts.app')

@section('main')
<div class="grid-x grid-padding-x splash-image">
    <div class="cell small-12">

        <div class="card">
            <div class="card-section">
                <a class="button small" style="background-color: #3b5999;" href="{{ url('/auth/facebook')}}" data-tooltip="Login with Facebook" tabindex="6">
                    <svg class="icon" style="font-size: 1.25em;vertical-align: bottom;"><use xlink:href="{{ asset('/images/icons.svg#icon-facebook') }}"></use></svg>
                    Login
                </a>
                <hr>
                {{--
                <a class="button" style="background-color: #55acee; padding: .5rem .75rem;" href="{{ url('/auth/twitter')}}" data-tooltip="Login with Twitter" tabindex="7">
                    <svg class="icon"><use xlink:href="#icon-twitter"></use></svg>
                    Login
                </a>

                <a class="button" style="background-color:#dd4b39;  padding: .5rem .75rem;" href="{{ url('/auth/google')}}" data-tooltip="Login with Google" tabindex="8">
                    <svg class="icon"><use xlink:href="#icon-google"></use></svg>
                    Login
                </a>
                --}}
                <form role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <label class="{{ $errors->has('email') ? 'is-invalid-label' : '' }}">Email
                        <input type="email" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'is-invalid-input' : '' }}" required autofocus tabindex="1">
                        @if ($errors->has('email'))<span class="form-error{{ $errors->has('email') ? ' is-visible' : '' }}">{{ $errors->first('email') }}</span>@endif
                    </label>


                    <label class="{{ $errors->has('password') ? 'is-invalid-label' : '' }}">Password
                        <input type="password" name="password" class="{{ $errors->has('password') ? 'is-invalid-input' : '' }}" aria-describedby="password-help" required tabindex="2" >
                        @if ($errors->has('password'))<span class="form-error{{ $errors->has('password') ? ' is-visible' : '' }}">{{ $errors->first('password') }}</span>@endif
                    </label>
                    <p class="help-text" id="password-help">
                        <a href="{{ url('/password/reset') }}" tabindex="3">Forgot Your Password?</a>
                    </p>

                    <button class="button expanded" tabindex="5">Login</button>

                    <label>
                        <input type="checkbox" name="remember" tabindex="4"> Keep me logged in
                    </label>

                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-section text-center">
                Don't have an account? <a href="{{ url('/register')}}" tabindex="9">Sign up.</a>
            </div>
        </div>

    </div>
</div>
@endsection
