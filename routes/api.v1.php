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

Route::group(['prefix' => 'password', 'namespace' => 'Password'], function () {

    Route::post('reset/{user}', 'MainController@reset');

    Route::group(['prefix' => 'request_reset'], function () {

        Route::group(['prefix' => 'sms'], function () {
            Route::post('start', 'SmsResetController@start');
            Route::post('token', 'SmsResetController@token');
        });

        Route::group(['prefix' => 'email'], function () {
            Route::post('start', 'EmailResetController@start');
        });

    });

});

Route::group(['prefix' => 'profile'], function () {

    Route::get('/', 'ProfileController@index');
    Route::put('/', 'ProfileController@save');
    Route::get('picture', 'ProfileController@picture');
    Route::post('picture', 'ProfileController@setPicture');

});

Route::group(['prefix' => 'orders'], function () {
    Route::get('/', 'OrderController@index');
    Route::get('/{model}', 'OrderController@view');
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

    Route::group(['prefix' => 'suppliers'], function () {
        Route::get('/', 'SupplierController@index');
        Route::get('/{model}', 'SupplierController@view');
        Route::get('/code/{code}', 'SupplierController@viewCode');
    });

});

Route::group(['prefix' => 'cart', 'namespace' => 'Cart'], function () {

    Route::group(['prefix' => 'transactions'], function () {
        Route::get('/{model}', 'TransactionController@get');
        
        Route::delete('/{id}', 'TransactionController@delete');

        Route::post('/{model}/purchase', 'TransactionController@purchase');
    });

});

Route::group(['prefix' => 'gateway', 'namespace' => 'Gateway'], function () {

    Route::group(['prefix' => 'client'], function () {
        Route::post('/token', 'ClientController@token');
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', 'CustomerController@index');
        Route::post('/', 'CustomerController@create');
        Route::get('/{model}', 'CustomerController@get');
        Route::get('/{model}/details', 'CustomerController@getDetails');
        Route::delete('/{model}', 'CustomerController@delete');
    });

    Route::group(['prefix' => 'payment'], function () {
        Route::post('/', 'PaymentController@create');
    });

});
