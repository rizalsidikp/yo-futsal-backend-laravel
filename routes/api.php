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

Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@register');
Route::get('users', 'Api\UserController@getUsers');
Route::get('storage/{filename}', function ($filename)
{
    return Image::make()->response();
});

Route::group(['middleware' => ['auth:api', 'checkheaders']], function(){
    Route::get('user', 'Api\UserController@getUser');
    Route::get('user/{id}', 'Api\UserController@show');
    Route::post('user/email', 'Api\UserController@findUserByEmail');
    Route::patch('user/update', 'Api\UserController@updateUser');
    Route::patch('user/password', 'Api\UserController@updatePassword');
    //team
    Route::resource('team', 'Api\TeamController');
    Route::get('myteam', 'Api\DetailTeamController@myTeam');
    Route::resource('detailteam', 'Api\DetailTeamController');
    //schedule
    Route::resource('schedule', 'Api\ScheduleController');
    Route::get('myschedule', 'Api\ScheduleController@mySchedule');
    //field
    Route::resource('field', 'Api\FieldController');
    Route::post('field/search', 'Api\FieldController@showByName');
    Route::group(['middleware' => 'checkfield'], function () {
        Route::patch('user/myfield', 'Api\FieldController@updateMyField');
    });
});