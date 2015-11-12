<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middleware' => 'auth'], function(){

    Route::get('test', 'TestController@test');

    Route::get('/', function () {

        Illuminate\Support\Facades\Redis::publish('rooms', json_encode(['room' => 'default_room']));
        return view('welcome');
    });


    resource('messages', 'MessagesController');
    resource('rooms', 'RoomsController');
    post('users/set_room', 'UsersController@setRoom');
    get('users/get_user', 'UsersController@getUser');
});

Route::controller('auth', 'Auth\AuthController');


