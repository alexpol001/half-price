<?php

use Illuminate\Support\Facades\Route;

Route::get('{path}/create/{relation}/{id}', 'Web\CrudController@create')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::post('{path}/create/{relation}/{id}', 'Web\CrudController@store')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::get('{path}/create', 'Web\CrudController@create')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::post('{path}/create', 'Web\CrudController@store')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::get('{path}/update/{id}', 'Web\CrudController@edit')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::post('{path}/update/{id}', 'Web\CrudController@update')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::delete('{path}/delete', 'Web\CrudController@singleDelete')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::delete('{path}/multi-delete', 'Web\CrudController@multiDelete')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::get('{path}', 'Web\CrudController@index')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::get('/', 'Web\Controller@index');
