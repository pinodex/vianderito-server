<?php

Route::get('/', 'MainController@index');

Route::post('cart', 'MainController@cart');

Route::post('epcs', 'MainController@epcs');

Route::post('products', 'MainController@products');

Route::post('clearance', 'MainController@clearance');

Route::group(['prefix' => 'transactions'], function () {
    Route::post('/', 'TransactionController@create');

    Route::put('/{model}/products', 'TransactionController@setProducts');
});
