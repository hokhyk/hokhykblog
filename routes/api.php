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

// For all users to see blogs in the system.
Route::group(['namespace' => 'Blog'], function() {

    Route::apiResource('/articles', 'ArticlesController')->except(['articles.store', 'articles.update', 'articles.destroy']);

});


// For Normal users to login.
Route::post('/login', 'UserAuthorization@Login');



// The following endpoints should be protected  before successful authentication.
Route::group(['namespace' => 'User', 'middleware' => 'auth:api'], function() {

    //    Logout
    Route::delete('/logout', 'AdminAuthorization@adminLogout');

    // For Users to view or modify his own information.
    Route::get('/users/{id}', 'UsersController@showUserInfo')->name('showUser');

    Route::put('/users/{id}', 'UsersController@updateUserInfo')->name('updateUser');



    // For users to view an article's detail.
    Route::get('/users/{user_id}/articles/{article_id}', 'UsersController@showOneArticle')->name('showOneUserArticle');

    // For users to view someone's articles.
    Route::get('/users/{user_id}/articles', 'UsersController@showArticles')->name('showUserArticles');


    // Allow authors and administrators to create or update articles.
    Route::apiResource('/articles', 'ArticlesController')->except(['articles.index', 'articles.show']);


});


