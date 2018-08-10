<?php

use Illuminate\Http\Request;

Route::get('/session', 'MainController@session');

Route::group([
    'as' => 'admin.',
    'prefix' => 'admin',
    'namespace' => 'Admin'],

    function () {
        Route::post('/login', 'MainController@login');
        Route::post('/logout', 'MainController@logout');

        Route::get('/permissions', 'MainController@permissions');

        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/', 'MainController@index');

            Route::group(['as' => 'accounts.', 'prefix' => 'accounts'], function () {
                Route::get('/', 'AccountController@index');

                Route::get('/{model}', 'AccountController@view');
                Route::patch('/{model}', 'AccountController@edit');
                Route::delete('/{model}', 'AccountController@delete');
                
                Route::post('/{model}/avatar', 'AccountController@avatar');
                Route::post('/{model}/reset_password', 'AccountController@resetPassword');
                Route::get('/{model}/logs', 'AccountController@logs');

                Route::post('/create', 'AccountController@create');
            });

            Route::group(['as' => 'groups.', 'prefix' => 'groups'], function () {
                Route::get('/', 'GroupController@index');
                Route::get('/all', 'GroupController@all');

                Route::patch('/{model}', 'GroupController@edit');
                Route::delete('/{model}', 'GroupController@delete');
                
                Route::post('/{model}/avatar', 'GroupController@avatar');
                Route::put('/{model}/permissions', 'GroupController@permissions');

                Route::post('/create', 'GroupController@create');
            });

            Route::group(['as' => 'permissions.', 'prefix' => 'permissions'], function () {
                Route::get('/all', 'PermissionController@all');
            });

            Route::group(['as' => 'categories.', 'prefix' => 'categories'], function () {
                Route::get('/', 'CategoryController@index');
                Route::get('/all', 'CategoryController@all');

                Route::get('/{model}', 'CategoryController@view');

                Route::patch('/{model}', 'CategoryController@edit');
                Route::delete('/{model}', 'CategoryController@delete');

                Route::post('/create', 'CategoryController@create');
            });

            Route::group(['as' => 'manufacturers.', 'prefix' => 'manufacturers'], function () {
                Route::get('/', 'ManufacturerController@index');
                Route::get('/all', 'ManufacturerController@all');

                Route::get('/{model}', 'ManufacturerController@view');

                Route::patch('/{model}', 'ManufacturerController@edit');
                Route::delete('/{model}', 'ManufacturerController@delete');

                Route::post('/create', 'ManufacturerController@create');
            });

            Route::group(['as' => 'products.', 'prefix' => 'products'], function () {
                Route::get('/', 'ProductController@index');
                Route::get('/all', 'ProductController@all');

                Route::get('/{model}', 'ProductController@view');
                Route::post('/{model}/picture', 'ProductController@picture');

                Route::patch('/{model}', 'ProductController@edit');
                Route::delete('/{model}', 'ProductController@delete');

                Route::post('/create', 'ProductController@create');
            });
        });
    }
);
