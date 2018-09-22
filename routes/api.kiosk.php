<?php

Route::get('/', 'MainController@index');

Route::post('cart', 'MainController@cart');

Route::post('epcs', 'MainController@epcs');
