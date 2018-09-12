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

    Route::post('password', 'AuthController@password');
    
    Route::get('me', 'AuthController@me');

});

Route::group(['prefix' => 'profile'], function () {

    Route::get('/', 'ProfileController@index');

    Route::put('/', 'ProfileController@save');

    Route::get('picture', 'ProfileController@picture');

    Route::post('picture', 'ProfileController@setPicture');

});

Route::group(['prefix' => 'verify', 'namespace' => 'Verify'], function () {

    Route::group(['prefix' => 'sms'], function () {
        Route::post('start', 'SmsVerificationController@start');
        Route::post('verify', 'SmsVerificationController@verify');
    });

});

Route::group(['prefix' => 'store', 'namespace' => 'Store'], function () {

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', 'ProductController@index');
        Route::get('/names', 'ProductController@names');
        Route::get('/{model}', 'ProductController@view');
        Route::get('/upc/{upc}', 'ProductController@viewUpc');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoryController@index');
        Route::get('/{model}', 'CategoryController@view');
    });

    Route::group(['prefix' => 'manufacturers'], function () {
        Route::get('/', 'ManufacturerController@index');
        Route::get('/{model}', 'ManufacturerController@view');
        Route::get('/code/{code}', 'ManufacturerController@viewCode');
    });

});
