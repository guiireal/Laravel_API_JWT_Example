<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'API', 'as' => 'api.'], function() {

    Route::post('/auth/login', 'AuthController@login')->name('login');

    Route::middleware('apiProtected')->group(function() {
        Route::post('/auth/logout', 'AuthController@logout')->name('logout');
        Route::post('me', 'AuthController@me')->name('me');
        Route::apiResource('companies', 'CompanyController');
    });
});
