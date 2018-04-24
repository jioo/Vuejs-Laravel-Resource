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

Route::get('articles', 'ArticleController@index');
Route::get('article/{id}', 'ArticleController@show');

// Athenticated api
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('article', 'ArticleController@store');
    Route::put('article', 'ArticleController@store');
    Route::delete('article/{id}', 'ArticleController@destroy');
});
