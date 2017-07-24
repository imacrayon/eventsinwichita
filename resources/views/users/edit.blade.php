@extends('users.profile')

@section('profile-content')
<div class="grid-container"  style="margin-bottom: 2rem;">
    <div class="grid-x grid-padding-x align-center">
        <div class="small-12 medium-6 cell">

            <form role="form" method="POST" action="{{ $user->url() . '/settings' }}">

                {{ method_field('PUT') }}

                {{ csrf_field() }}

                <fieldset>

                    <legend>Notifications</legend>

                    <label class="{{ $errors->has('name') ? 'is-invalid-label' : '' }}">
                        <input type="checkbox" name="settings[email_notifications]" value="1" class="{{ $errors->has('settings.email_notifications') ? 'is-invalid-input' : '' }}" {{ old('settings.email_notifications', $user->settings['email_notifications']) ? 'checked' : '' }}>
                        Email me when someone comments on an event I'm watching.
                        @if ($errors->has('settings.email_notifications'))<span class="form-error{{ $errors->has('settings.email_notifications') ? ' is-visible' : '' }}">{{ $errors->first('settings.email_notifications') }}</span>@endif
                    </label>

                </fieldset>

                <fieldset>

                    <legend>Information</legend>

                    @if (strpos($user->avatar, 'gravatar') !== false)
                        <div class="callout">
                            <p>To change your profile picture use a <a href="https://en.gravatar.com/emails/" target="_blank">Gravatar</a> account.</p>
                        </div>
                    @endif

                    <label class="{{ $errors->has('name') ? 'is-invalid-label' : '' }}">Name
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="{{ $errors->has('name') ? 'is-invalid-input' : '' }}" required autofocus>
                        @if ($errors->has('name'))<span class="form-error{{ $errors->has('name') ? ' is-visible' : '' }}">{{ $errors->first('name') }}</span>@endif
                    </label>

                    <label class="{{ $errors->has('email') ? 'is-invalid-label' : '' }}">Email
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="{{ $errors->has('email') ? 'is-invalid-input' : '' }}" required>
                        @if ($errors->has('email'))<span class="form-error{{ $errors->has('email') ? ' is-visible' : '' }}">{{ $errors->first('email') }}</span>@endif
                    </label>

                </fieldset>
                <fieldset>

                    <legend>Password</legend>

                    <label class="{{ $errors->has('password') ? 'is-invalid-label' : '' }}">Password
                        <input type="password" name="password" class="{{ $errors->has('password') ? 'is-invalid-input' : '' }}">
                        @if ($errors->has('password'))<span class="form-error{{ $errors->has('password') ? ' is-visible' : '' }}">{{ $errors->first('password') }}</span>@endif
                    </label>

                    <label class="{{ $errors->has('password_confirmation') ? 'is-invalid-label' : '' }}">Confirm Password
                        <input type="password" name="password_confirmation" class="{{ $errors->has('password_confirmation') ? 'is-invalid-input' : '' }}">
                        @if ($errors->has('password_confirmation'))<span class="form-error{{ $errors->has('password_confirmation') ? ' is-visible' : '' }}">{{ $errors->first('password_confirmation') }}</span>@endif
                    </label>

                </fieldset>

                <button class="button expanded" tabindex="5">Save Changes</button>

            </form>

        </div>
    </div>
</div>
@endsection
