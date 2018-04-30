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

Route::post('register', 'UserController@register');

Route::get('movies', 'MovieController@index');
Route::get('movie/{id}', 'MovieController@show');
Route::get('category', 'CategoryController@index');

// Athenticated api
//Route::group(['middleware' => 'auth:api'], function () {
    Route::post('movie', 'MovieController@store');
    Route::put('movie', 'MovieController@store');
    Route::delete('movie/{id}', 'MovieController@destroy');

    Route::post('category', 'CategoryController@store');
    Route::put('category', 'CategoryController@store');
    Route::delete('category/{id}', 'CategoryController@destroy');
//});
