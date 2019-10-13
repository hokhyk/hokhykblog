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

//  Voyager calculator API module
// By default, middleware 'api' already has throttle:60,1 setup. here I just override it with 120,1 meaning 120 hits in 1 minute.
Route::group(['prefix' => 'voyager/v1', 'middleware' => ['api', 'throttle:120,1'], 'namespace' => '\Voyager\V1'], function() {

    Route::post('/add', 'AddController@add')->name('voyager.add');

    Route::post('/sub', 'SubController@sub')->name('voyager.sub');

    Route::post('/mul', 'MulController@mul')->name('voyager.mul');

    Route::post('/div', 'DivController@div')->name('voyager.div');

    // The following endpoints should be protected  before successful authentication.
    Route::group(['middleware' => 'auth:api'], function() {

        //Moving the route here under 'auth:api' will ensure api authentication.
//        Route::post('/add', 'AddController@add')->name('voyager.add');
//        Route::post('/sub', 'SubController@sub')->name('voyager.sub');
//        Route::post('/mul', 'MulController@mul')->name('voyager.mul');
//        Route::post('/div', 'DivController@div')->name('voyager.div');


    });
});


//  Blog module
Route::group(['prefix' => 'blog', 'middleware' => ['api', ], 'namespace' => '\Blog'], function() {

    Route::get('/articles', 'ArticlesController@index')->name('articles.index');

    Route::get('/articles/{id}', 'ArticlesController@show')->name('articles.show');

    // The following endpoints should be protected  before successful authentication.
    Route::group(['middleware' => 'auth:api'], function() {

        //    Allow authors and administrators to create or update articles.

        Route::post('/articles', 'ArticlesController@store')->name('articles.store');

        Route::put('/articles/{id}', 'ArticlesController@upate')->name('articles.update');

        Route::delete('/articles', 'ArticlesController@destroy')->name('articles.destroy');

    });
});




// User module
Route::group(['prefix' => 'users', 'middleware' => ['api', ], 'namespace' => '\User'], function() {

    // For Normal users to login.
    Route::post('/login', 'UserAuthenticationController@login')->name('users.login');
    // TODO: register, reset password, unregister( if allowed...)

    // For Users to view  Author information.
    Route::get('/{id}', 'UsersController@showUserInfo')->name('users.showUser');


    // For users to view an article's detail .
    Route::get('/{user_id}/articles/{article_id}', 'UsersController@showOneArticle')->name('users.showOneUserArticle');

    // For users to view someone's articles.
    Route::get('/{user_id}/articles', 'UsersController@showArticles')->name('users.showUserArticles');


    // The following endpoints should be protected  before successful authentication.
    Route::group(['middleware' => 'auth:api'], function() {

        //    Logout
        Route::post('/logout', 'UserAuthenticationController@logout')->name('users.logout');

        // For Users to  modify his own information.
        Route::put('/{id}', 'UsersController@updateUserInfo')->name('users.updateUser');

    });
});






// Administrator module
Route::group(['prefix' => 'admin', 'middleware' => ['api', ], 'namespace' => '\Admin'], function() {

    Route::post('/login', 'AdminAuthenticationController@adminLogin')->name('admin.login');


    // The following endpoints should be protected  before successful authentication.
    Route::group(['middleware' => 'auth:api'], function() {

        //    Logout
        Route::post('/logout', 'AdminAuthenticationController@adminLogout')->name('admin.logout');

        //TODO: For administrators to manage administrators in App\Entities\AdminUser Model.
        //  Hereby in this practise exercise They share the same model. Will be using multi-auth models/guards later.
        //        Route::apiResource('/administrator', 'AdministratorsController');

        // For administrators to manage normal users in App\Entities\User Model.
        Route::apiResource('/manageusers', 'ManageUsersController');

        //TODO: get Administrator operation logs.
       //      Route::get('/admin_user_logs', 'AdminOperationLogController@index');
    });
});









































