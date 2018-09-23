<?php

Route::get('/', 'MainController@index');

Route::post('cart', 'MainController@cart');

Route::post('epcs', 'MainController@epcs');

Route::group(['prefix' => 'transactions'], function () {
    Route::post('/', 'TransactionController@create');

    Route::put('/{model}/products', 'TransactionController@setProducts');
});
