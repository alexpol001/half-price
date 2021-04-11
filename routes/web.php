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

/**
 *
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('/logout', 'Web\Controller@logout')->name('logout');

Route::get('/login', 'Web\Controller@login')->name('login');

Route::post('/login', 'Web\Controller@postLogin');

Route::get('/signup', 'Web\Controller@signUp');

Route::post('/signup', 'Web\Controller@signUpPost');

Route::get('/', 'Web\Controller@index');

Route::get('/about', 'Web\Controller@about');

Route::get('/faq', 'Web\Controller@faq');

Route::get('/offer', 'Web\Controller@offer');

Route::get('/politics', 'Web\Controller@politics');

Route::get('/map', 'Web\Controller@map');

Route::get('/shop/{id}', 'Web\Controller@shop');

Route::get('/product/{id}', 'Web\Controller@product');

Route::get('/bar-code/{id}', 'Web\Controller@barCode');

Route::get('/feedback', 'Web\Controller@feedback');

Route::post('/feedback', 'Web\Controller@feedbackPost');

Auth::routes();


