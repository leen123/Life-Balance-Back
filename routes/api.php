<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Middleware\Cors;

//Route::middleware([Cors::class])->group(function () {

Route::post('register', 'API\UserController@register');
Route::post('login', 'API\UserController@login');
Route::post('forgot-password', 'ForgotPasswordController@sendOtpCode');
Route::post('validate-forgot-password-otp', 'ForgotPasswordController@validateOtpCode');
Route::post('reset-password', 'ForgotPasswordController@resetPassword');
Route::post('/save-file', 'API\AttachmentControllers@uploadFile');
/** for guest apis */


Route::middleware('auth:api')->group(function () {

    Route::get('getProfile', 'API\UserController@getProfile');
    Route::put('update', 'API\UserController@updateUser');
    Route::PUT('changePassword', 'API\UserController@changePassword');
    Route::get('all-user', 'API\UserController@getAllUser');
    Route::get('/me', 'API\UserController@getUser');

    Route::get('/users', 'API\UserController@index');
    Route::get('/configs', 'API\ConfigController@index');
    Route::get('roles', 'PermissionController@Permission');


    Route::group(['prefix' => 'permissions'], function () {
        Route::post('add', 'PermissionController@addPermission'); // Add Permission
        Route::put('edit', 'PermissionController@editPermission');                       //  Edit Permission
        Route::delete('delete/{id}', 'PermissionController@deletePermission');           // Delete Permission
        Route::get('showByID/{id}', 'PermissionController@showPermissionByID');           // Show Permission By ID
        Route::get('all', 'PermissionController@showAllPermission');                      // Show All Permission
        Route::put('giveUserPermission/{userId}/{PermissionId}', 'PermissionController@giveUserPermission');             // give User Permission
        Route::put('permissionToRole/{PermissionId}/{RoleId}', 'PermissionController@permissionToRole'); //permission To Role
    });
    Route::group(['prefix' => 'roles'], function () {
        Route::post('add', 'RoleController@addRole');                       // Add Role
        Route::put('edit', 'RoleController@editRole');                       //  Edit Role
        Route::delete('delete/{id}', 'RoleController@deleteRole');           // Delete Role
        Route::get('showByID/{id}', 'RoleController@showRoleByID');           // Show Role By ID
        Route::get('all', 'RoleController@showAllRole');                      // Show All Role
        Route::post('giveUserRole/{userId}/{RoleID}', 'RoleController@giveUserRole');
        Route::get('allPermissionForRole/{RoleID}', 'RoleController@allPermissionForRole');
    });
    Route::group(['prefix' => 'userPermission'], function () {
        Route::post('add', 'UserPermissionController@addUserPermission');                       // Add User Permission
        Route::put('edit', 'UserPermissionController@editUserPermission');                       //  Edit User Permission
        Route::delete('delete/{id}', 'UserPermissionController@deleteUserPermission');           // Delete User Permission
        Route::get('showByID/{id}', 'UserPermissionController@showUserPermissionByID');           // Show User Permission By ID
        Route::get('all', 'UserPermissionController@showAllUserPermission');                      // Show All User Permission
    });
    Route::group(['prefix' => 'userRole'], function () {
        Route::post('add', 'UserRoleController@addUserRole');                       // Add User Role
        Route::put('edit', 'UserRoleController@editUserUserRole');                       //  Edit User Role
        Route::delete('delete/{id}', 'UserRoleController@deleteUserRole');           // Delete User Role
        Route::get('showByID/{id}', 'UserRoleController@showUserRoleByID');           // Show User Role By ID
        Route::get('all', 'UserRoleController@showAllUserRole');                      // Show All User Role
    });
    Route::group(['prefix' => 'rolePermission'], function () {
        Route::post('add', 'RolePermissionController@addRolePermission');                       // Add  Role Permission
        Route::put('edit', 'RolePermissionController@editRolePermission');                       //  Edit  Role Permission
        Route::delete('delete/{id}', 'RolePermissionController@deleteRolePermission');           // Delete Role Permission
        Route::get('showByID/{id}', 'RolePermissionController@showRolePermissionByID');           // Show  Role Permission By ID
        Route::get('all', 'RolePermissionController@showAllRolePermissions');                      // Show All  Role Permission
    });



    Route::post('journal', 'API\UserController@getJournalData');
    Route::get('dashboard' , 'API\DashboardController@index');


    Route::group(['prefix' => 'section'], function () {//ok
        Route::post('/' , 'API\SectionController@store');
        Route::get('/', 'API\SectionController@index');
        Route::get('/{id}', 'API\SectionController@show');
        Route::put('/{id}', 'API\SectionController@update');
        Route::delete('/{id}', 'API\SectionController@destroy');
    });


    Route::group(['prefix' => 'activity'], function () {//ok
        Route::get('/', 'API\ActivityController@index');
        Route::post('/' , 'API\ActivityController@store');
        Route::get('/{id}', 'API\ActivityController@show');
        Route::put('/{id}', 'API\ActivityController@update');
        Route::delete('/{id}', 'API\ActivityController@destroy');
    });


    Route::group(['prefix' => 'mood'], function () {//ok
        Route::get('/', 'API\MoodController@index');
        Route::post('/' , 'API\MoodController@store');
        Route::get('/{id}', 'API\MoodController@show');
        Route::put('/{id}', 'API\MoodController@update');
        Route::delete('/{id}', 'API\MoodController@destroy');
    });

    Route::group(['prefix' => 'habit'], function () {//ok
        Route::get('/', 'API\HabitsController@index');
        Route::post('/' , 'API\HabitsController@store');
        Route::get('/{id}', 'API\HabitsController@show');
        Route::put('/{id}', 'API\HabitsController@update');
        Route::delete('/{id}', 'API\HabitsController@destroy');
    });

    Route::put('/update-habit/{id}' , 'API\HabitsController@updateHabit');

    Route::group(['prefix' => 'images'], function () {//ok
        Route::get('/', 'API\ImageController@index');
        Route::post('/' , 'API\ImageController@store');
        Route::get('/{id}', 'API\ImageController@show');
        Route::put('/{id}', 'API\ImageController@update');
        Route::delete('/{id}', 'API\ImageController@destroy');
    });

    Route::group(['prefix' => 'goal'], function () {//ok
        Route::get('/', 'API\GoalsController@index');
        Route::post('/' , 'API\GoalsController@store');
        Route::get('/{id}', 'API\GoalsController@show');
        Route::put('/{id}', 'API\GoalsController@update');
        Route::delete('/{id}' , 'API\GoalsController@destroy');
    });

    Route::put('/goals/complete/{id}' , 'API\GoalsController@completeGoal');


    Route::group(['prefix' => 'task'], function () {//ok
        Route::post('/' , 'API\GoalsController@storeTask');
        Route::put('/{id}' , 'API\GoalsController@updateFullTask');
        Route::delete('/{id}', 'API\GoalsController@destroyTask');
    });


    Route::group(['prefix' => 'reward'], function () {//ok
        Route::get('/', 'API\RewardController@index');
        Route::post('/' , 'API\RewardController@store');
        Route::get('/{id}', 'API\RewardController@show');
        Route::put('/{id}', 'API\RewardController@update');
        Route::delete('/{id}', 'API\RewardController@destroy');
    });

    Route::group(['prefix' => 'badge'], function () {//ok
        Route::get('/', 'API\BadgesController@index');
        Route::post('/' , 'API\BadgesController@store');
        Route::get('/{id}', 'API\BadgesController@show');
        Route::put('/{id}', 'API\BadgesController@update');
        Route::delete('/{id}', 'API\BadgesController@destroy');
    });

    Route::group(['prefix' => 'ads'], function () {//ok
        Route::get('/', 'API\AdController@index');
    });

    Route::group(['prefix' => 'coupon'], function () {//ok
        Route::get('/', 'API\CouponController@index');
        Route::post('/consume' , 'API\CouponController@consume');
        Route::get('/getMyCoupons', 'API\CouponController@myCoupons');
    });

    Route::put('finished-or-unFinished{id}', 'API\GoalsController@updateTask');


    Route::post('activities/do-activity' , 'API\ActivityController@doAction' );

    Route::post('activities/do-quick-entry-activity' , 'API\ActivityController@doQuickEntryActivity' );

    Route::put('tasks/finished-or-unFinished/{id}' , 'API\GoalsController@updateTask');

    Route::post('moods/do-mood' , 'API\MoodController@doMood');

    Route::get('getHomeData' , 'API\UserController@getHomeData' );

    Route::post('entity-mood' , 'API\MoodController@entityMood');




});


// notifications
Route::post('update-fcm-token' , 'NotificationController@add_fcm_token')->middleware('auth:api');
Route::get('notifications' , 'NotificationController@index')->middleware('auth:api');



