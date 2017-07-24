<?php

namespace App\Http\Controllers\Auth;

use Mail;
use App\User;
use App\Mail\Welcome;
use Illuminate\Http\Request;
use App\Mail\NewRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * The default user avatar image.
     *
     * @var string
     */
    protected $avatarDefault = 'identicon';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $avatar = 'https://gravatar.com/avatar/';
        $avatar .= md5(strtolower(trim($data['email'])));
        $avatar .= '?d=' . urlencode($this->avatarDefault);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'avatar'   => $avatar,
            'settings'  => [
                'email_notifications' => true
            ]
        ]);
    }

    /* --------------------------------------------------------------------- */
    /* ------------------------ Overwritten Methods ------------------------ */
    /* --------------------------------------------------------------------- */

    /**
     * The user has been registered. Let's set some default settings to make him/her happy like ":)".
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User                $user
     *
     * @return void
     */
    protected function registered(Request $request, User $user)
    {
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
    }
}
