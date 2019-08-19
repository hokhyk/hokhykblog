<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

 Route::get('/manageusers', 'ManageUsersController@index')->name('showUsers');

 Route::get('/manageusers/{id}', 'ManageUsersController@show')->name('showUser');

 Route::post('/manageusers', 'ManageUsersController@store')->name('storeUser');

 Route::put('/manageusers/{id}', 'ManageUsersController@update')->name('updateUser');

 Route::delete('/manageusers/{id}', 'ManageUsersController@destroy')->name('deleteUser');

