<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

trait SocialAuthentication
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $profile = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

        return empty($profile->email)
            ? $this->sendFailedResponse('No email returned from '.ucwords($provider).' provider.')
            : $this->loginOrCreateAccount($profile, $provider);
    }

    protected function sendSuccessResponse()
    {
        return redirect()->intended($this->redirectTo);
    }

    protected function sendFailedResponse($message = null)
    {
        return redirect()->route('login')
            ->withErrors(['social' => $message ?: 'Unable to login, try another login method.']);
    }

    private function loginOrCreateAccount($profile, $provider)
    {
        $user = User::where("settings->{$provider}_id", $profile->getId())->first();

        if (! $user) {
            $user = User::where('email', $profile->getEmail())->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $profile->getName(),
                'email' => $profile->getEmail(),
            ]);
        }

        $user->update([
            'settings' => [
                "{$provider}_id" => $profile->getId(),
                "{$provider}_token" => $profile->token,
                'expires_at' => Carbon::now()
                                    ->addSeconds($profile->expiresIn)
                                    ->format('Y-m-d H:i:s'),
            ],
        ]);

        Auth::login($user, true);

        return $this->sendSuccessResponse();
    }
}
