<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/', 'EventController@index');

Route::get('/events', 'EventController@index');
Route::get('/events/{event}', 'EventController@show');

Route::get('/places', 'PlaceController@index');
Route::get('/places/{place}', 'PlaceController@show');
Route::get('/places/{place}/events', 'PlaceController@events');

Route::get('/users/{user}/settings', 'UserController@edit');
Route::put('/users/{user}/settings', 'UserController@update');
Route::get('/users/{user}/{view?}', 'UserController@show');

Route::get('/contact', 'ContactController@index');
Route::post('/contact', 'ContactController@send');
