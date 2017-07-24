@extends('layouts.main')

@section('content')
<div class="head-bar" style="margin-bottom: 2rem;">
    <div class="head-bar-container">
        <h1 class="head-bar-title">
            <svg class="icon" style="top: -.2em;"><use xlink:href="{{ asset('/images/icons.svg#icon-mail') }}"></use></svg>
            Contact Us
        </h1>
    </div>
</div>
<div class="grid-container">
    <div class="grid-x grid-padding-x align-center">
        <div class="small-12 medium-6 cell">

            @if (session('status'))
                <div class="callout success">
                    {{ session('status') }}
                </div>
            @endif

            <form role="form" method="POST" action="{{ '/contact' }}">

                {{ csrf_field() }}

                @if (auth()->check())
                    <?php $user = auth()->user(); ?>
                    <label class="{{ $errors->has('name') ? 'is-invalid-label' : '' }}">Full Name
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="{{ $errors->has('name') ? 'is-invalid-input' : '' }}" required autofocus>
                        @if ($errors->has('name'))<span class="form-error{{ $errors->has('name') ? ' is-visible' : '' }}">{{ $errors->first('name') }}</span>@endif
                    </label>

                    <label class="{{ $errors->has('email') ? 'is-invalid-label' : '' }}">Email
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="{{ $errors->has('email') ? 'is-invalid-input' : '' }}" required>
                        @if ($errors->has('email'))<span class="form-error{{ $errors->has('email') ? ' is-visible' : '' }}">{{ $errors->first('email') }}</span>@endif
                    </label>
                @else
                    <label class="{{ $errors->has('name') ? 'is-invalid-label' : '' }}">Full Name
                        <input type="text" name="name" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid-input' : '' }}" required autofocus>
                        @if ($errors->has('name'))<span class="form-error{{ $errors->has('name') ? ' is-visible' : '' }}">{{ $errors->first('name') }}</span>@endif
                    </label>

                    <label class="{{ $errors->has('email') ? 'is-invalid-label' : '' }}">Email
                        <input type="email" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'is-invalid-input' : '' }}" required>
                        @if ($errors->has('email'))<span class="form-error{{ $errors->has('email') ? ' is-visible' : '' }}">{{ $errors->first('email') }}</span>@endif
                    </label>
                @endif

                <label class="{{ $errors->has('message') ? 'is-invalid-label' : '' }}">Message
                    <textarea name="message" class="{{ $errors->has('message') ? 'is-invalid-input' : '' }}" required></textarea>
                    @if ($errors->has('message'))<span class="form-error{{ $errors->has('message') ? ' is-visible' : '' }}">{{ $errors->first('message') }}</span>@endif
                </label>

                <button class="button expanded" tabindex="5">Send Message</button>

            </form>

        </div>
    </div>
</div>
@endsection
