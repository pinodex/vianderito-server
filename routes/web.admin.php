<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainController@index')->name('index');

Route::get('login', 'MainController@login')->name('login');

Route::get('password_reset/{account}', 'MainController@passwordReset')->name('passwordReset');

Route::get('/{any}', 'MainController@index')->where('any', '.*');
