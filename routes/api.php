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

Route::group(['namespace' => 'Blog'], function() {

    Route::get('/articles', 'ArticlesController@index')->name('showArticles');

    Route::get('/articles/{id}', 'ArticlesController@show')->name('showArticle');

    Route::post('/articles', 'ArticlesController@store')->name('storeArticle');

    Route::put('/articles/{id}', 'ArticlesController@update')->name('updateArticle');

    Route::delete('/articles/{id}', 'ArticlesController@destroy')->name('deleteArticle');
});


Route::group(['namespace' => 'User'], function() {

    Route::get('/users/{id}', 'UsersController@show')->name('showUser');

    Route::put('/users/{id}', 'UsersController@update')->name('updateUser');
});


