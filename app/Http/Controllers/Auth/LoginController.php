<?php

namespace App\Http\Controllers\Auth;

use Mail;
use App\User;
use App\Mail\Welcome;
use App\Mail\NewRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Contracts\Factory as Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Laravel Sociaite.
     *
     * @var \Laravel\Socialite\Contracts\Factory
     */
    protected $socialite;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Socialite $socialite)
    {
        $this->middleware('guest')->except('logout');

        $this->socialite = $socialite;
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return $this->socialite->driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {

        try {
            $user = $this->socialite->driver($provider)->user();
        } catch (Exception $e) {
            return redirect('/login');
        }

        $authUser = $this->findOrCreateUser($user);

        app('auth')->login($authUser, true);

        return redirect($this->redirectTo);
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $user
     * @return User
     */
    private function findOrCreateUser($providerUser)
    {
        // User has a password account but logins in with Facebook.
        if ($authUser = User::where('email', $providerUser->getEmail())->first()) {
            $authUser->update([
                'avatar' => $providerUser->getAvatar()
            ]);
            return $authUser;
        }

        $user = User::create([
            'name'     => $providerUser->getName(),
            'email'    => $providerUser->getEmail(),
            'avatar'   => $providerUser->getAvatar(),
            'settings' => [
                'email_notifications' => true
            ]
        ]);

        \App\Activity::create([
            'subject_id'   => $user->id,
            'user_agent'   => \App\Activity::getUserAgent(),
            'ip_address'   => \App\Activity::getIpAddress(),
            'country'      => \App\Activity::getCountry(),
            'subject_type' => 'App\User',
            'name'         => 'created_user',
            'user_id'      => $user->id,
        ]);

        Mail::to($user->email)->queue(new Welcome($user));

        Mail::to(env('MAIL_CONTACT_ADDRESS'))->send(new NewRegistration($user));

        return $user;
    }
}
