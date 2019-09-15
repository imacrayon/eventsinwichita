<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('Profile/Edit', [
            'user' => $request->user()->only([
                'name',
                'email',
            ]),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user = tap($request->user())->update($request->only(['name', 'email']));

        if ($request->get('password')) {
            $user->update(['password' => Hash::make($request->get('password'))]);
        }

        return Redirect::route('profile.edit')->with('success', 'Profile updated.');
    }
}
