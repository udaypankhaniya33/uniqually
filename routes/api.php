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

// Admin endpoints/routes
Route::group(['prefix' => 'admin', 'middleware' => 'cors'], function()
{
    Route::post('login', 'API\Admin\UserController@login');
    Route::middleware('auth:api')->group( function () {
        Route::get('users', 'API\Admin\UserController@index');
        Route::post('coupon-codes', 'API\Admin\CouponCodesController@store');
    });
});
