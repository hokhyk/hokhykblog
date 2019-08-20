<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// For Normal users to login.
Route::post('/login', 'UserAuthenticationController@Login');

//    Logout
Route::delete('/logout', 'UserAuthenticationController@adminLogout');



// The following endpoints should be protected  before successful authentication.
Route::group(['middleware' => 'auth:api'], function() {


    // For Users to view or modify his own information.
    Route::get('/users/{id}', 'UsersController@showUserInfo')->name('showUser');

    Route::put('/users/{id}', 'UsersController@updateUserInfo')->name('updateUser');



    // For users to view an article's detail.
    Route::get('/users/{user_id}/articles/{article_id}', 'UsersController@showOneArticle')->name('showOneUserArticle');

    // For users to view someone's articles.
    Route::get('/users/{user_id}/articles', 'UsersController@showArticles')->name('showUserArticles');

});


