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
    Route::apiResource('/articles', 'ArticlesController');
});


Route::group(['namespace' => 'User'], function() {

    Route::get('/users/{id}', 'UsersController@show')->name('showUser');

    Route::put('/users/{id}', 'UsersController@update')->name('updateUser');

    Route::get('/users/{id}/articles', 'UsersController@showArticles')->name('showUserArticles');

});


