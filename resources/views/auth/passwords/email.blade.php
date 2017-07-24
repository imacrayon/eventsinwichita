@extends('layouts.app')

@section('main')
<div class="grid-x grid-padding-x splash-image">
    <div class="cell">

        <div class="card">
            <div class="card-head">
                <h2 class="card-title">Forgot Password</h2>
            </div>
            <div class="card-section">
                @if (session('status'))
                    <div class="callout success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                    {{ csrf_field() }}

                    <label class="{{ $errors->has('email') ? 'is-invalid-label' : '' }}">Email
                        <input type="email" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'is-invalid-input' : '' }}" required autofocus>
                        @if ($errors->has('email'))<span class="form-error{{ $errors->has('email') ? ' is-visible' : '' }}">{{ $errors->first('email') }}</span>@endif
                    </label>

                    <button type="submit" class="button expanded" style="margin: 0;">
                        Send Password Reset Link
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
