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

        Route::post('/request_password_reset', 'MainController@requestPasswordReset');

        Route::post('/reset_password/{account}', 'MainController@resetPassword');

        Route::post('/change_password', 'MainController@changePassword');

        Route::get('/permissions', 'MainController@permissions');

        Route::group(['middleware' => ['auth:admin', 'allow_enabled']], function () {
            Route::get('/', 'MainController@index');

            Route::group(['as' => 'accounts.', 'prefix' => 'accounts'], function () {
                Route::get('/', 'AccountController@index');

                Route::get('/{model}', 'AccountController@view');
                Route::patch('/{model}', 'AccountController@edit');
                Route::delete('/{model}', 'AccountController@delete');
                
                Route::post('/{model}/avatar', 'AccountController@avatar');
                Route::post('/{model}/reset_password', 'AccountController@resetPassword');

                Route::post('/{model}/enable', 'AccountController@enable');
                Route::post('/{model}/disable', 'AccountController@disable');

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
                Route::get('/byId', 'CategoryController@byId');
                Route::get('/all', 'CategoryController@all');

                Route::get('/{model}', 'CategoryController@view');

                Route::patch('/{model}', 'CategoryController@edit');
                Route::delete('/{model}', 'CategoryController@delete');

                Route::post('/create', 'CategoryController@create');
            });

            Route::group(['as' => 'suppliers.', 'prefix' => 'suppliers'], function () {
                Route::get('/', 'SupplierController@index');
                Route::get('/byId', 'SupplierController@byId');
                Route::get('/all', 'SupplierController@all');

                Route::get('/{model}', 'SupplierController@view');

                Route::patch('/{model}', 'SupplierController@edit');
                Route::delete('/{model}', 'SupplierController@delete');

                Route::post('/create', 'SupplierController@create');
            });

            Route::group(['as' => 'products.', 'prefix' => 'products'], function () {
                Route::get('/', 'ProductController@index');
                Route::get('/byId', 'ProductController@byId');
                Route::get('/all', 'ProductController@all');

                Route::get('/{model}', 'ProductController@view');
                Route::post('/{model}/picture', 'ProductController@picture');

                Route::patch('/{model}', 'ProductController@edit');
                Route::delete('/{model}', 'ProductController@delete');

                Route::post('/create', 'ProductController@create');
            });

            Route::group(['as' => 'inventories.', 'prefix' => 'inventories'], function () {
                Route::get('/', 'InventoryController@index');
                Route::get('/byId', 'InventoryController@byId');

                Route::get('/{model}', 'InventoryController@view');

                Route::patch('/{model}', 'InventoryController@edit');
                Route::delete('/{model}', 'InventoryController@delete');

                Route::post('/create', 'InventoryController@create');
            });

            Route::group(['as' => 'coupons.', 'prefix' => 'coupons'], function () {
                Route::get('/', 'CouponController@index');

                Route::get('/{model}', 'CouponController@view');

                Route::patch('/{model}', 'CouponController@edit');
                Route::delete('/{model}', 'CouponController@delete');

                Route::post('/create', 'CouponController@create');
            });

            Route::group(['as' => 'users.', 'prefix' => 'users'], function () {
                Route::get('/', 'UserController@index');

                Route::get('/{model}', 'UserController@view');
                Route::patch('/{model}', 'UserController@edit');
                Route::delete('/{model}', 'UserController@delete');
                
                Route::post('/{model}/avatar', 'UserController@avatar');
                Route::post('/{model}/reset_password', 'UserController@resetPassword');
            });
        });
    }
);
