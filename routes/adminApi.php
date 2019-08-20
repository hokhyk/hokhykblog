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

// For Administrators' login.

Route::post('/login', 'AdminAuthenticationController@adminLogin');



// The following endpoints should be protected  before successful authentication.

    //    Logout
    Route::delete('/logout', 'AdminAuthenticationController@adminLogout');


    // For administrators to manage administrators in App\Entities\AdminUser Model.
    Route::apiResource('/administrator', 'AdministratorsController');


    // For administrators to manage normal users in App\Entities\User Model.
    Route::apiResource('/manageusers', 'ManageUsersController');


    //TODO: get Administrator operation logs.
   //      Route::get('/admin_user_logs', 'AdminOperationLogController@index');
