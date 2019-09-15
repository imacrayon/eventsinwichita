<?php

Route::namespace('Auth')->group(function () {
    Route::get('login')->name('login')->uses('LoginController@showLoginForm');
    Route::post('login')->name('login.attempt')->uses('LoginController@login');
    Route::post('logout')->name('logout')->uses('LoginController@logout');

    Route::get('/login/{provider}', 'LoginController@redirectToProvider')->name('login.social');
    Route::get('/login/{provider}/callback', 'LoginController@handleProviderCallback');

    Route::get('register')->name('register')->uses('RegisterController@showRegistrationForm');
    Route::post('register')->name('register.attempt')->uses('RegisterController@register');

    Route::get('password/reset')->name('password.request')->uses('ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email')->name('password.email')->uses('ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}')->name('password.reset')->uses('ResetPasswordController@showResetForm');
    Route::post('password/reset')->name('password.update')->uses('ResetPasswordController@reset');
});

Route::middleware('auth')->group(function () {
    Route::get('profile')->name('profile.edit')->uses('ProfileController@edit');
    Route::post('profile')->name('profile.update')->uses('ProfileController@update');

    Route::get('events/{event}/edit')->name('events.edit')->uses('EventController@edit');
    Route::put('events/{event}')->name('events.update')->uses('EventController@update');
    Route::delete('events/{event}')->name('events.destroy')->uses('EventController@destroy');
    Route::put('events/{event}/restore')->name('events.restore')->uses('EventController@restore');
});

Route::get('events/create')->name('events.create')->uses('EventController@create');
Route::post('events')->name('events.store')->uses('EventController@store');
Route::get('events/{event}')->name('events.show')->uses('EventController@show');

Route::get('events/{event}/proposals/create')->name('events.proposals.create')->uses('EventProposalController@create');
Route::post('events/{event}/proposals')->name('events.proposals.store')->uses('EventProposalController@store');

Route::get('/')->name('events.index')->uses('EventController@index');
