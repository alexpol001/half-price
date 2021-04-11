<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'Admin\Controller@login');

Route::post('/login', 'Admin\Controller@postLogin');

Route::get('{path}/create/{relation}/{id}', 'Admin\CrudController@create')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::post('{path}/create/{relation}/{id}', 'Admin\CrudController@store')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::get('{path}/create', 'Admin\CrudController@create')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::post('{path}/create', 'Admin\CrudController@store')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::get('{path}/update/{id}', 'Admin\CrudController@edit')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::post('{path}/update/{id}', 'Admin\CrudController@update')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::delete('{path}/delete', 'Admin\CrudController@singleDelete')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::delete('{path}/multi-delete', 'Admin\CrudController@multiDelete')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::get('{path}', 'Admin\CrudController@index')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::get('/', 'Admin\Controller@index');
