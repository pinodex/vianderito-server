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

            Route::group(['as' => 'stats.', 'prefix' => 'stats'], function () {
                Route::get('/counts', 'StatsController@counts');
            });

            Route::group(['as' => 'accounts.', 'prefix' => 'accounts'], function () {
                Route::get('/', 'AccountController@index');

                Route::get('/logs', 'AccountController@allLogs');
                Route::post('/create', 'AccountController@create');

                Route::get('/{model}', 'AccountController@view');
                Route::patch('/{model}', 'AccountController@edit');
                Route::delete('/{model}', 'AccountController@delete');
                
                Route::post('/{model}/avatar', 'AccountController@avatar');
                Route::post('/{model}/reset_password', 'AccountController@resetPassword');

                Route::post('/{model}/enable', 'AccountController@enable');
                Route::post('/{model}/disable', 'AccountController@disable');

                Route::post('/{model}/restore', 'AccountController@restore');
                Route::post('/{model}/destroy', 'AccountController@destroy');

                Route::get('/{model}/logs', 'AccountController@logs');
            });

            Route::group(['as' => 'departments.', 'prefix' => 'departments'], function () {
                Route::get('/', 'DepartmentController@index');
                Route::get('/all', 'DepartmentController@all');

                Route::patch('/{model}', 'DepartmentController@edit');
                Route::delete('/{model}', 'DepartmentController@delete');

                Route::post('/{model}/restore', 'DepartmentController@restore');
                Route::post('/{model}/destroy', 'DepartmentController@destroy');
                
                Route::post('/{model}/avatar', 'DepartmentController@avatar');
                Route::put('/{model}/permissions', 'DepartmentController@permissions');

                Route::post('/create', 'DepartmentController@create');
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

                Route::post('/{model}/restore', 'CategoryController@restore');
                Route::post('/{model}/destroy', 'CategoryController@destroy');

                Route::post('/{model}/restore', 'CategoryController@restore');
                Route::post('/{model}/destroy', 'CategoryController@destroy');

                Route::post('/create', 'CategoryController@create');
            });

            Route::group(['as' => 'suppliers.', 'prefix' => 'suppliers'], function () {
                Route::get('/', 'SupplierController@index');
                Route::get('/byId', 'SupplierController@byId');
                Route::get('/all', 'SupplierController@all');

                Route::get('/{model}', 'SupplierController@view');

                Route::patch('/{model}', 'SupplierController@edit');
                Route::delete('/{model}', 'SupplierController@delete');

                Route::post('/{model}/restore', 'SupplierController@restore');
                Route::post('/{model}/destroy', 'SupplierController@destroy');

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

                Route::post('/{model}/restore', 'ProductController@restore');
                Route::post('/{model}/destroy', 'ProductController@destroy');

                Route::get('/{model}/losses', 'ProductController@losses');

                Route::post('/create', 'ProductController@create');
            });

            Route::group(['as' => 'inventories.', 'prefix' => 'inventories'], function () {
                Route::get('/', 'InventoryController@index');
                Route::get('/byId', 'InventoryController@byId');

                Route::get('/{model}', 'InventoryController@view');

                Route::patch('/{model}', 'InventoryController@edit');
                Route::delete('/{model}', 'InventoryController@delete');

                Route::post('/{model}/restore', 'InventoryController@restore');
                Route::post('/{model}/destroy', 'InventoryController@destroy');

                Route::post('/create', 'InventoryController@create');

                Route::group(['as' => 'losses.', 'prefix' => '{inventory}/losses/'], function () {
                    Route::get('/', 'InventoryLossController@index');
                    Route::post('/', 'InventoryLossController@create');

                    Route::patch('/{model}', 'InventoryLossController@edit');
                    Route::delete('/{model}', 'InventoryLossController@delete');
                });
            });

            Route::group(['as' => 'coupons.', 'prefix' => 'coupons'], function () {
                Route::get('/', 'CouponController@index');

                Route::get('/{model}', 'CouponController@view');

                Route::patch('/{model}', 'CouponController@edit');
                Route::delete('/{model}', 'CouponController@delete');

                Route::post('/{model}/restore', 'CouponController@restore');
                Route::post('/{model}/destroy', 'CouponController@destroy');

                Route::post('/create', 'CouponController@create');
            });

            Route::group(['as' => 'users.', 'prefix' => 'users'], function () {
                Route::get('/', 'UserController@index');

                Route::get('/{model}', 'UserController@view');
                Route::patch('/{model}', 'UserController@edit');
                Route::delete('/{model}', 'UserController@delete');

                Route::get('/{model}/payment_methods', 'UserController@paymentMethods');
                Route::get('/{model}/payments', 'UserController@payments');
                Route::get('/{model}/purchases', 'UserController@purchases');

                Route::post('/{model}/restore', 'UserController@restore');
                Route::post('/{model}/destroy', 'UserController@destroy');
                
                Route::post('/{model}/avatar', 'UserController@avatar');
                Route::post('/{model}/reset_password', 'UserController@resetPassword');
            });

            Route::group(['as' => 'reports.', 'prefix' => 'reports', 'namespace' => 'Reports'], function () {
                
                Route::group(['as' => 'inventory.', 'prefix' => 'inventory'], function () {
                    Route::get('/', 'InventoryController@index');
                    Route::get('/graph', 'InventoryController@graph');
                });

                Route::group(['as' => 'sales.', 'prefix' => 'sales'], function () {
                    Route::get('/', 'SalesController@index');
                    Route::get('/graph', 'SalesController@graph');
                    Route::get('/graph/options', 'SalesController@graphOptions');
                });

                Route::group(['as' => 'purchases.', 'prefix' => 'purchases'], function () {
                    Route::get('/', 'PurchasesController@index');
                    Route::get('/graph', 'PurchasesController@graph');
                });

            });
        });
    }
);
