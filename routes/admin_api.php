<?php

use App\Http\Middleware\CheckType;
use App\Http\Resources\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('test',function (){

//     return 'test';
// });
Route::middleware(['auth:api'/*, 'CheckType:1'*/])->group(function () {


    Route::group(['prefix' => 'admin'], function () {

        //ADS
        Route::get('ads', 'AdminApi\AdController@index');
        Route::post('ads', 'AdminApi\AdController@store');
        Route::get('ads/{id}', 'AdminApi\AdController@show');
        Route::put('ads/{id}', 'AdminApi\AdController@update');
        Route::delete('ads/{id}', 'AdminApi\AdController@destroy');

        //Coupon
        Route::get('coupon', 'AdminApi\CouponController@index');
        Route::post('coupon', 'AdminApi\CouponController@store');
        Route::get('coupon/{id}', 'AdminApi\CouponController@show');
        Route::put('coupon/{id}', 'AdminApi\CouponController@update');
        Route::delete('coupon/{id}', 'AdminApi\CouponController@destroy');


        //Companies
        Route::get('companies', 'AdminApi\CompanyController@index');
        Route::post('companies', 'AdminApi\CompanyController@store');
        Route::get('companies/{id}', 'AdminApi\CompanyController@show');
        Route::put('companies/{id}', 'AdminApi\CompanyController@update');
        Route::delete('companies/{id}', 'AdminApi\CompanyController@destroy');

        //Notifications
        Route::post('send-notifications', 'AdminApi\NotificationController@send');


        //ActivityLog
        Route::get('activity_log', 'AdminApi\ActivityLogController@index');
        Route::get('activity_log/{id}', 'AdminApi\ActivityLogController@show');
        Route::delete('activity_log/{id}', 'AdminApi\ActivityLogController@destroy');
        Route::post('activity_log_destroy', 'AdminApi\ActivityLogController@delete_log');


        //Roles
        Route::get('roles', 'AdminApi\RoleController@index');
        Route::post('roles', 'AdminApi\RoleController@store');
        Route::get('roles/{id}', 'AdminApi\RoleController@show');
        Route::put('roles/{id}', 'AdminApi\RoleController@update');
        Route::delete('roles/{id}', 'AdminApi\RoleController@destroy');


        //Users
        Route::get('users', 'AdminApi\UserController@index');
        Route::post('users', 'AdminApi\UserController@store');
        Route::get('users/{id}', 'AdminApi\UserController@show');
        Route::put('users/{id}', 'AdminApi\UserController@update');
        Route::delete('users/{id}', 'AdminApi\UserController@destroy');


        //Employees
        Route::get('employees', 'AdminApi\EmployeeController@index');
        Route::post('employees', 'AdminApi\EmployeeController@store');
        Route::get('employees/{id}', 'AdminApi\EmployeeController@show');
        Route::put('employees/{id}', 'AdminApi\EmployeeController@update');
        Route::delete('employees/{id}', 'AdminApi\EmployeeController@destroy');

        Route::post('edit-profile','AdminApi\ProfileController@update');
    });
});


Route::get('test', function () {

    $user = App\User::find(1);

    $user->notify((new App\Notifications\GeneralNotification(['title' => 'test', 'description' => 'test description'])));
});
