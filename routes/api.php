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
Route::get('storage/{filename}', function ($filename)
{
    return Image::make()->response();
});

Route::group(['middleware' => ['auth:api', 'checkheaders']], function(){
    Route::get('user', 'Api\UserController@getUser');
    Route::get('user/{id}', 'Api\UserController@show');
    Route::patch('user/update', 'Api\UserController@updateUser');
    Route::patch('user/password', 'Api\UserController@updatePassword');
    Route::group(['middleware' => 'checkfield'], function () {
        Route::patch('user/myfield', 'Api\FieldController@updateMyField');
    });
});