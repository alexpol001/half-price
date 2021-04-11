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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('{path}/create', 'Api\CrudController@store')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::delete('{path}/delete', 'Api\CrudController@singleDelete')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::delete('{path}/multi-delete', 'Api\CrudController@multiDelete')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::post('{path}/sort', 'Api\CrudController@sort')->where( 'path' , '([A-z\d\-\/_.]+)?');

//Route::get('2/{path}/', 'Api\CrudController@index2')->where( 'path' , '([A-z\d\-\/_.]+)?');

Route::get('{path}/', 'Api\CrudController@index')->where( 'path' , '([A-z\d\-\/_.]+)?');
