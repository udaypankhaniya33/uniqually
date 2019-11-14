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
        // Admin Users
        Route::get('users', 'API\Admin\UserController@index');
        Route::post('users', 'API\Admin\UserController@store');
        // Coupon Codes
        Route::post('coupon-codes', 'API\Admin\CouponCodesController@store');
        Route::put('coupon-codes/{id}', 'API\Admin\CouponCodesController@update');
        Route::get('coupon-codes', 'API\Admin\CouponCodesController@index');
        Route::delete('coupon-codes/{id}', 'API\Admin\CouponCodesController@delete');
        Route::post('coupon-codes/csv', 'API\Admin\CouponCodesController@uploadCsv');
        // Subscribers
        Route::get('subscribers', 'API\Admin\SubscribersController@index');
        Route::post('subscribers/resend-coupon', 'API\Admin\SubscribersController@resendCoupon');
        // Package Categories
        Route::get('package-categories', 'API\Admin\PackageCategoriesController@index');
        Route::post('package-categories', 'API\Admin\PackageCategoriesController@store');
        Route::put('package-categories/{id}', 'API\Admin\PackageCategoriesController@update');
        Route::delete('package-categories/{id}', 'API\Admin\PackageCategoriesController@delete');
        // Packages
        Route::get('packages', 'API\Admin\PackagesController@index');
        Route::post('packages', 'API\Admin\PackagesController@store');
        Route::put('packages/{id}', 'API\Admin\PackagesController@update');
        Route::delete('packages/{id}', 'API\Admin\PackagesController@delete');
        // Package Attributes
        Route::get('package-attributes', 'API\Admin\PackageAttributesController@index');
        Route::post('package-attributes', 'API\Admin\PackageAttributesController@store');
        Route::put('package-attributes/{id}', 'API\Admin\PackageAttributesController@update');
    });
});
// Guest endpoints/routes
Route::group(['prefix' => 'guest', 'middleware' => 'cors'], function()
{
    Route::post('subscribe', 'API\Guest\SubscribersController@store');
});
