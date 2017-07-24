<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', 'UserController@me');
Route::get('/users/{user}/notifications', 'UserNotificationController@index');
Route::delete('/users/{user}/notifications/{notification}', 'UserNotificationController@destroy');

Route::post('/geocode', 'PlaceController@geocode');

Route::resource('events', 'EventController', ['except' => [
    'create', 'edit'
]]);
Route::get('/events/{event}/comments', 'EventCommentController@index');
Route::post('/events/{event}/comments', 'EventCommentController@store');
Route::get('/events/{event}/subscriptions', 'EventSubscriptionController@index');
Route::post('/events/{event}/subscriptions', 'EventSubscriptionController@store');
Route::delete('/events/{event}/subscriptions', 'EventSubscriptionController@destroy');

Route::resource('places', 'PlaceController', ['except' => [
    'create', 'edit'
]]);
Route::get('/places/{place}/comments', 'PlaceCommentController@index');
Route::post('/places/{place}/comments', 'PlaceCommentController@store');
Route::get('/places/{place}/subscriptions', 'PlaceSubscriptionController@index');
Route::post('/places/{place}/subscriptions', 'PlaceSubscriptionController@store');
Route::delete('/places/{place}/subscriptions', 'PlaceSubscriptionController@destroy');

Route::resource('tags', 'TagController', ['except' => [
    'create', 'edit'
]]);

Route::resource('comments', 'CommentController', ['except' => [
    'index', 'store'
]]);

Route::resource('activities', 'ActivityController', ['only' => [
    'index'
]]);
