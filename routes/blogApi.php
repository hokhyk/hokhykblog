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

// For all users to see Blog in the system.

//    Route::apiResource('/articles', 'ArticlesController')->except(['articles.store', 'articles.update', 'articles.destroy']);


//    // Allow authors and administrators to create or update articles.
Route::group(['middleware' => 'auth:api'], function() {

    Route::apiResource('/articles', 'ArticlesController')->except(['articles.index', 'articles.show']);
});



