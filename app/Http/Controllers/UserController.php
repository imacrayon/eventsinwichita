<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->middleware('auth')->except('show', 'events', 'places', 'comments');

        $this->users = $users;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, $view = 'show')
    {
        return view("users.{$view}", compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->merge(['settings' => [
            'email_notifications' => $request->has('settings.email_notifications')
        ]]);

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'settings.email_notifications' => 'boolean'
        ]);

        $data = $request->all();

        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
            unset($data['password_confirmation']);
        }

        $this->users->update($data, $user);

        return redirect($user->url() . '/settings')->with('flash', 'Settings updated.');
    }
}
