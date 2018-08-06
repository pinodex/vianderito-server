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

Route::get('/', 'MainController@index');

Route::group(['prefix' => 'auth'], function () {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');

    Route::post('register', 'AuthController@register');
    
    Route::get('me', 'AuthController@me');

});

Route::group(['prefix' => 'verify', 'namespace' => 'Verify'], function () {

    Route::group(['prefix' => 'sms'], function () {

        Route::post('start', 'SmsVerificationController@start');

        Route::post('verify', 'SmsVerificationController@verify');

    });

});
